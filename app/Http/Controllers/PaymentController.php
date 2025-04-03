<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Check if a user has paid for a specific formation
     */
    public function checkPaymentStatus($userId, $formationId)
    {
        // Verify if user and formation exist
        $user = User::find($userId);
        $formation = Formation::find($formationId);

        if (!$user || !$formation) {
            return response()->json([
                'hasPaid' => false,
                'message' => 'Utilisateur ou formation non trouvé'
            ], 404);
        }

        // Check if there's a successful payment record
        $payment = Payment::where('user_id', $userId)
                         ->where('formation_id', $formationId)
                         ->where('status', 'success')
                         ->first();

        return response()->json([
            'hasPaid' => $payment ? true : false
        ]);
    }

    /**
     * Process payment for a formation
     */
    public function processPayment(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id',
            'formationId' => 'required|exists:formations,id',
            'amount' => 'required|numeric',
            'cardLast4' => 'required|string|size:4',
            'cardName' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // In a real application, you would integrate with a payment gateway here
        // For this example, we'll simulate a successful payment

        try {
            // Create payment record
            $payment = Payment::create([
                'user_id' => $request->userId,
                'formation_id' => $request->formationId,
                'montant' => $request->amount,
                'methode' => 'Credit Card **** ' . $request->cardLast4,
                'status' => 'success',
                'date' => Carbon::now(),
                'confirmation' => 'PAYMENT-' . uniqid(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'confirmationCode' => $payment->confirmation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of payments
     */
    public function index()
    {
        $payments = Payment::with(['user'])->get();
        return response()->json($payments);
    }

    /**
     * Get payments for a specific user
     */
    public function getUserPayments($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $payments = Payment::where('user_id', $userId)->get();
        return response()->json($payments);
    }
}