<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use App\Mail\PaymentSuccessful;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentController extends Controller
{
    protected $paypalClient;

    public function __construct()
    {
        // Initialize Stripe with your secret key
        Stripe::setApiKey(config('services.stripe.secret'));
        
        // Initialize PayPal client
        if (config('services.paypal.mode') === 'sandbox') {
            $environment = new SandboxEnvironment(
                config('services.paypal.client_id'),
                config('services.paypal.client_secret')
            );
        } else {
            $environment = new ProductionEnvironment(
                config('services.paypal.client_id'),
                config('services.paypal.client_secret')
            );
        }
        
        $this->paypalClient = new PayPalHttpClient($environment);
    }

    /**
     * Create a payment intent with Stripe
     */
    public function createStripeIntent(Request $request)
    {
        try {
            Log::info('Creating Stripe intent', $request->all());
            Log::info('Finding user');
            $user = User::find($request->userId);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            Log::info('Finding formation');
            $formation = Formation::find($request->formationId);
            if (!$formation) {
                return response()->json(['error' => 'Formation not found'], 404);
            }
            
            // Create a Payment Intent
            Log::info('Setting Stripe API key');
            Stripe::setApiKey(config('services.stripe.secret'));
            
            Log::info('Creating payment intent with amount: ' . $request->amount);
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) $request->amount, // Make sure it's an integer
                'currency' => 'eur',
                'metadata' => [
                    'userId' => $user->id,
                    'userName' => $user->name,
                    'formationId' => $formation->id,
                    'formationTitle' => $formation->titre,
                ],
            ]);

            Log::info('Payment intent created successfully');
            
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating payment intent: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
 * Create a PayPal order
 */
public function createPayPalOrder(Request $request)
{
    try {
        Log::info('Creating PayPal order', $request->all());
        
        $user = User::find($request->userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $formation = Formation::find($request->formationId);
        if (!$formation) {
            return response()->json(['error' => 'Formation not found'], 404);
        }
        
        // Ensure consistent amount formatting - Always use decimal format for PayPal
        $amount = $request->amount;
        if ($amount > 100) { // This assumes no product costs more than €100
            $amount = $amount / 100;
        }
        
        // Format amount properly with 2 decimal places
        $formattedAmount = number_format($amount, 2, '.', '');
        
        Log::info('PayPal amount after conversion: ' . $formattedAmount);
        
        $paypalRequest = new OrdersCreateRequest();
        $paypalRequest->prefer('return=representation');
        
        // Define front-end URLs for success and cancel
        // Make sure this URL matches what your front-end is actually expecting
        $frontendUrl = config('app.frontend_url', 'http://127.0.0.1:8000');
        
        $successUrl = $frontendUrl . '/formation/' . $formation->id . '?paypal_status=success&userId=' . $user->id . '&formationId=' . $formation->id;
        $cancelUrl = $frontendUrl . '/formation/' . $formation->id . '?paypal_status=cancel';
        
        // Construct the request body
        $paypalRequest->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => 'formation_' . $formation->id,
                'description' => $formation->titre ?? 'vue',
                'custom_id' => $user->id . '_' . $formation->id,
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => $formattedAmount
                ]
            ]],
            'application_context' => [
                'brand_name' => config('app.name', 'Laravel'),
                'landing_page' => 'NO_PREFERENCE', // Changed from BILLING to NO_PREFERENCE
                'user_action' => 'PAY_NOW',
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl
            ]
        ];
        
        Log::info('PayPal request data:', [
            'amount' => $formattedAmount,
            'currency' => 'EUR',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl
        ]);
        
        $response = $this->paypalClient->execute($paypalRequest);
        
        // Log the entire response for debugging
        Log::info('PayPal complete response', [
            'response' => json_decode(json_encode($response->result), true)
        ]);
        
        // Find the approval URL
        $approvalUrl = null;
        foreach ($response->result->links as $link) {
            if ($link->rel === 'approve') {
                $approvalUrl = $link->href;
                break;
            }
        }
        
        if (!$approvalUrl) {
            Log::error('PayPal approval URL not found in response');
            return response()->json(['error' => 'Approval URL not found in PayPal response'], 500);
        }
        
        Log::info('PayPal order created successfully', [
            'orderId' => $response->result->id,
            'approvalUrl' => $approvalUrl
        ]);
        
        return response()->json([
            'orderId' => $response->result->id,
            'approvalUrl' => $approvalUrl
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error creating PayPal order: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => 'PayPal error: ' . $e->getMessage(), 
            'message' => 'Please check server logs for details'
        ], 500);
    }
}

    /**
     * Capture a PayPal order after approval
     */
    public function capturePayPalOrder(Request $request)
    {
        $request->validate([
            'orderId' => 'required|string',
            'userId' => 'required|exists:users,id',
            'formationId' => 'required|exists:formations,id',
        ]);
        
        try {
            Log::info('Capturing PayPal order: ' . $request->orderId);
            
            // Create a request to capture the order
            $captureRequest = new OrdersCaptureRequest($request->orderId);
            $captureRequest->prefer('return=representation');
            
            // Execute the request
            $response = $this->paypalClient->execute($captureRequest);
            
            Log::info('PayPal capture response status: ' . $response->result->status, [
                'order_id' => $request->orderId
            ]);
            
            if ($response->result->status === 'COMPLETED') {
                // Check if payment already exists to prevent duplicates
                $existingPayment = Payment::where('paypal_order_id', $request->orderId)->first();
                if ($existingPayment) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Paiement déjà enregistré.',
                    ]);
                }
                
                // Get payment details
                $captureId = $response->result->purchase_units[0]->payments->captures[0]->id;
                $amount = $response->result->purchase_units[0]->payments->captures[0]->amount->value;
                $currency = $response->result->purchase_units[0]->payments->captures[0]->amount->currency_code;
                
                Log::info('PayPal payment details:', [
                    'capture_id' => $captureId,
                    'amount' => $amount,
                    'currency' => $currency
                ]);
                
                // Get user and formation for email
                $user = User::find($request->userId);
                $formation = Formation::find($request->formationId);
                
                // Save payment record
                $payment = new Payment();
                $payment->user_id = $request->userId;
                $payment->formation_id = $request->formationId;
                $payment->amount = $amount;
                $payment->Pays = $request->Pays;
                $payment->ville = $request->ville;
                $payment->adresse = $request->adresse;
                $payment->code_postal = $request->code_postal;
                $payment->currency = strtolower($currency);
                $payment->paypal_order_id = $request->orderId;
                $payment->paypal_capture_id = $captureId;
                $payment->payment_method = 'paypal';
                $payment->status = 'completed';
                $payment->save();
                
                // Send payment confirmation email
                try {
                    Mail::to($user->email)->send(new PaymentSuccessful($user, $formation, $payment));
                    Log::info('Payment confirmation email sent to: ' . $user->email);
                } catch (\Exception $e) {
                    Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                    // Continue execution even if email fails
                }
                
                Log::info('PayPal payment saved successfully');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement PayPal enregistré avec succès.',
                ]);
                
            } else {
                Log::warning('PayPal payment not completed', [
                    'status' => $response->result->status
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Le paiement PayPal n\'a pas été complété.',
                    'status' => $response->result->status
                ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('PayPal capture error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement du paiement PayPal.',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle PayPal success redirection
     */
    public function paypalSuccess(Request $request)
    {
        Log::info('PayPal success callback received', $request->all());
        
        // Here you can implement any necessary redirection logic
        // This method should be linked to route('paypal.success')
        
        return view('payment.success');
    }
    
    /**
     * Handle PayPal cancel redirection
     */
    public function paypalCancel(Request $request)
    {
        Log::info('PayPal cancel callback received', $request->all());
        return view('payment.cancel');
    }

    /**
     * Confirm payment and save to database
     */
    public function confirmStripePayment(Request $request)
    {
        $request->validate([
            'paymentIntentId' => 'required|string',
            'userId' => 'required|exists:users,id',
            'formationId' => 'required|exists:formations,id',
        ]);

        try {
            // Retrieve the payment intent to verify its status
            $paymentIntent = PaymentIntent::retrieve($request->paymentIntentId);
            
            // Check if the payment was successful
            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Le paiement n\'a pas été complété.',
                ], 400);
            }

            // Check if payment already exists to prevent duplicates
            $existingPayment = Payment::where('stripe_payment_intent_id', $request->paymentIntentId)->first();
            if ($existingPayment) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement déjà enregistré.',
                ]);
            }

            // Get user and formation for email
            $user = User::find($request->userId);
            $formation = Formation::find($request->formationId);

            // Save payment record
            $payment = new Payment();
            $payment->user_id = $request->userId;
            $payment->formation_id = $request->formationId;
            $payment->amount = $paymentIntent->amount / 100;
            $payment->Pays = $request->Pays;
            $payment->ville = $request->ville;
            $payment->adresse = $request->adresse;
            $payment->code_postal = $request->code_postal;
            $payment->currency = $paymentIntent->currency;
            $payment->stripe_payment_intent_id = $paymentIntent->id;
            $payment->payment_method = 'stripe';
            $payment->status = 'completed';
            $payment->save();
            
            // Send payment confirmation email
            try {
                Mail::to($user->email)->send(new PaymentSuccessful($user, $formation, $payment));
                Log::info('Payment confirmation email sent to: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                // Continue execution even if email fails
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès.',
            ]);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            Log::error('Payment confirmation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement du paiement.',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has paid for a formation
     */
    public function checkPaymentStatus($userId, $formationId)
    {
        $payment = Payment::where('user_id', $userId)
                        ->where('formation_id', $formationId)
                        ->where('status', 'completed')
                        ->first();

        return response()->json([
            'hasPaid' => $payment !== null,
        ]);
    }
}