<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    public function __construct()
    {
        // Initialize Stripe with your secret key
        Stripe::setApiKey(config('services.stripe.secret'));
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

        // Save payment record
        $payment = new Payment();
        $payment->user_id = $request->userId;
        $payment->formation_id = $request->formationId;
        $payment->amount = $paymentIntent->amount / 100; // Using 'amount' as in the migration
        $payment->currency = $paymentIntent->currency;
        $payment->stripe_payment_intent_id = $paymentIntent->id;
        $payment->status = 'completed';
        $payment->save();
        
        // Add this success response - it was missing!
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