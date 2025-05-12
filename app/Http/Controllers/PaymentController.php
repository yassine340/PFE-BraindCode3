<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\FormationUser;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use App\Mail\PaymentSuccessful;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// Suppression des anciens imports PayPal qui causaient l'erreur

class PaymentController extends Controller
{
    protected $paypalClient;
    protected $platformFeePercentage = 5; // 5% pour la plateforme
    protected $coachPercentage = 95; // 95% pour le coach

    public function __construct()
    {
        // Initialize Stripe with your secret key
        Stripe::setApiKey(config('services.stripe.secret'));
        
        // Temporairement désactiver l'initialisation PayPal qui cause l'erreur
        // L'initialisation PayPal sera réactivée lorsque vous déciderez d'implémenter PayPal
    }

    /**
     * Calculate coach amount based on total payment amount
     * 
     * @param float $amount The total payment amount
     * @return float The coach's share (95% of total)
     */
    protected function calculateCoachAmount($amount)
    {
        return round(($amount * $this->coachPercentage) / 100, 2);
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
            Log::info('PayPal functionality temporarily disabled');
            return response()->json(['error' => 'PayPal integration is currently under maintenance'], 503);
            
            // Le code PayPal a été temporairement désactivé pour résoudre l'erreur
            // Il sera restauré une fois que vous aurez configuré correctement le SDK
        } catch (\Exception $e) {
            Log::error('Error creating PayPal order: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
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
            Log::info('PayPal functionality temporarily disabled');
            return response()->json(['error' => 'PayPal integration is currently under maintenance'], 503);
            
            // Le code PayPal a été temporairement désactivé pour résoudre l'erreur
            // Il sera restauré une fois que vous aurez configuré correctement le SDK
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
        
        // Extract any needed parameters from the request
        $orderId = $request->get('token'); // PayPal typically returns token parameter
        $formationId = $request->get('formationId');
        
        // Redirect to the Vue.js route
        return redirect()->to('http://127.0.0.1:8000/formations/' . $formationId . '?payment_status=success&order_id=' . $orderId);
    }

    /**
     * Handle PayPal cancel redirection
     */
    public function paypalCancel(Request $request)
    {
        Log::info('PayPal cancel callback received', $request->all());
        
        // Extract any needed parameters
        $formationId = $request->get('formationId');
        
        // Redirect to the Vue.js route
        return redirect()->to('http://127.0.0.1:8000/formations/' . $formationId . '?payment_status=cancelled');
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

            // Get payment amount in appropriate format
            $amount = $paymentIntent->amount / 100;
            
            // Calculate coach amount (95% of total)
            $coachAmount = $this->calculateCoachAmount($amount);

            // Save payment record
            $payment = new Payment();
            $payment->user_id = $request->userId;
            $payment->formation_id = $request->formationId;
            $payment->amount = $amount;
            $payment->coach_amount = $coachAmount;
            $payment->Pays = $request->Pays;
            $payment->ville = $request->ville;
            $payment->adresse = $request->adresse;
            $payment->code_postal = $request->code_postal;
            $payment->currency = $paymentIntent->currency;
            $payment->stripe_payment_intent_id = $paymentIntent->id;
            $payment->payment_method = 'stripe';
            $payment->status = 'completed';
            $payment->save();
            
            // Créer l'entrée dans formation_user avec la date d'expiration
            $dateAchat = Carbon::now();
            $dateExpiration = $this->calculerDateExpiration($dateAchat, $formation->duree);
            
            // Vérifier si une entrée existe déjà pour ce user et cette formation
            $formationUser = FormationUser::where('user_id', $user->id)
                                        ->where('formation_id', $formation->id)
                                        ->first();
                                        
            if ($formationUser) {
                // Si l'entrée existe déjà, mettre à jour les dates
                $formationUser->update([
                    'date_achat' => $dateAchat,
                    'date_expiration' => $dateExpiration,
                ]);
            } else {
                // Sinon créer une nouvelle entrée
                FormationUser::create([
                    'user_id' => $user->id,
                    'formation_id' => $formation->id,
                    'date_achat' => $dateAchat,
                    'date_expiration' => $dateExpiration,
                ]);
            }
            
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
    
    /**
     * Get total number of unique buyers for formations of a specific coach.
     */
    public function getTotalBuyersForCoach($coachId)
    {
        $totalBuyers = Payment::join('formations', 'payments.formation_id', '=', 'formations.id')
            ->where('formations.user_id', $coachId)
            ->distinct('payments.user_id')
            ->count('payments.user_id');

        return response()->json([
            'coach_id' => $coachId,
            'total_buyers' => $totalBuyers
        ]);
    }
    
    /**
     * Get total money earned by a specific coach.
     */
    public function getTotalMoneyForCoach($coachId)
    {
        $totalMoney = Payment::join('formations', 'payments.formation_id', '=', 'formations.id')
            ->where('formations.user_id', $coachId)
            ->sum('payments.coach_amount');

        return response()->json([
            'coach_id' => $coachId,
            'total_money' => $totalMoney
        ]);
    }
    
    /**
     * Get total money earned by each coach from their formations.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCoachEarnings()
    {
        $coachEarnings = DB::table('formations')
            ->join('payments', 'formations.id', '=', 'payments.formation_id')
            ->join('users', 'formations.user_id', '=', 'users.id')
            ->select(
                'formations.user_id as coach_id',
                'users.first_name',
                'users.last_name',
                'users.email',
                DB::raw('SUM(payments.coach_amount) as total_money')
            )
            ->groupBy('formations.user_id', 'users.first_name', 'users.last_name', 'users.email')
            ->orderBy('formations.user_id')
            ->get();

        return response()->json($coachEarnings);
    }
    
    public function listS3Files()
    {
        try {
            // S'assurer que l'utilisateur est authentifié et autorisé (à personnaliser)
            // $this->middleware('auth');
            
            // Récupérer les fichiers par dossier
            $s3 = Storage::disk('s3');
            
            $result = [
                'videos' => $this->getFilesFromDirectory('videos'),
                'documents' => $this->getFilesFromDirectory('documents')
            ];
            
            // Ajouter des statistiques générales
            $result['stats'] = [
                'total_files' => count($result['videos']) + count($result['documents']),
                'total_videos' => count($result['videos']),
                'total_documents' => count($result['documents'])
            ];
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la liste des fichiers S3: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors de la récupération des fichiers: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Récupérer les fichiers d'un dossier spécifique sur S3
     *
     * @param string $directory Nom du dossier
     * @return array Liste des fichiers
     */
    private function getFilesFromDirectory($directory)
    {
        $s3 = Storage::disk('s3');
        $files = $s3->files($directory);
        
        return collect($files)->map(function($path) use ($s3) {
            $filename = basename($path);
            $size = $s3->size($path);
            $lastModified = Carbon::createFromTimestamp($s3->lastModified($path));
            
            // URL présignée valide pendant 30 minutes
            $url = $s3->temporaryUrl($path, now()->addMinutes(30));
            
            return [
                'filename' => $filename,
                'path' => $path,
                'size' => $this->formatFileSize($size),
                'size_raw' => $size,
                'last_modified' => $lastModified->format('Y-m-d H:i:s'),
                'last_modified_human' => $lastModified->diffForHumans(),
                'url' => $url,
            ];
        })->values()->toArray();
    }
    
    /**
     * Formater la taille du fichier en unités lisibles
     *
     * @param int $bytes Taille en octets
     * @return string Taille formatée
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes > 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Calcule la date d'expiration en fonction de la durée
     */
    private function calculerDateExpiration($dateAchat, $duree)
    {
        // Convertir la date d'achat en Carbon si ce n'est pas déjà fait
        $dateAchat = $dateAchat instanceof Carbon ? $dateAchat : Carbon::parse($dateAchat);
        
        // Ajouter des logs pour le débogage
        Log::info('Calcul date d\'expiration', ['duree' => $duree, 'type' => gettype($duree)]);
        
        // Si la durée est un nombre simple (sans texte)
        if (is_numeric($duree)) {
            // Interpréter directement comme des jours (pas comme des mois)
            $jours = (float) $duree;
            
            // Conserver la valeur exacte sans arrondir
            Log::info('Durée exacte en jours', ['jours' => $jours]);
            
            // Pour gérer les fractions de jours, convertir en heures et minutes
            $joursEntiers = floor($jours);
            $fractionJour = $jours - $joursEntiers;
            $heures = floor($fractionJour * 24);
            $minutes = floor(($fractionJour * 24 - $heures) * 60);
            $secondes = floor((($fractionJour * 24 - $heures) * 60 - $minutes) * 60);
            
            Log::info('Décomposition de la durée', [
                'jours_entiers' => $joursEntiers,
                'fraction_jour' => $fractionJour,
                'heures' => $heures,
                'minutes' => $minutes,
                'secondes' => $secondes
            ]);
            
            return $dateAchat->copy()
                ->addDays($joursEntiers)
                ->addHours($heures)
                ->addMinutes($minutes)
                ->addSeconds($secondes);
        }
        
        // Le reste de la fonction reste inchangé pour les durées avec texte
        if (preg_match('/(\d+)\s*mois/i', $duree, $matches)) {
            return $dateAchat->copy()->addMonths((int)$matches[1]);
        } elseif (preg_match('/(\d+)\s*jour/i', $duree, $matches)) {
            return $dateAchat->copy()->addDays((int)$matches[1]);
        } elseif (preg_match('/(\d+)\s*semaine/i', $duree, $matches)) {
            return $dateAchat->copy()->addWeeks((int)$matches[1]);
        } elseif (preg_match('/(\d+)\s*an/i', $duree, $matches)) {
            return $dateAchat->copy()->addYears((int)$matches[1]);
        }
        
        // Par défaut, si le format n'est pas reconnu, ajouter 30 jours
        Log::warning('Format de durée non reconnu: ' . $duree . '. Utilisation de 30 jours par défaut.');
        return $dateAchat->copy()->addDays(30); // Par défaut 30 jours
    }
    public function getUserPayments($userId)
    {
        $payments = Payment::where('user_id', $userId)->get();

        return response()->json($payments);
    }
    public function getFormationPayments($formationId)
    {
        $payments = Payment::where('formation_id', $formationId)->get();

        return response()->json($payments);
    }
}