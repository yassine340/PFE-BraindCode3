<?php
namespace App\Http\Controllers;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Notifications\FormateurValidé;
use App\Notifications\FormateurRejete;
use App\Models\Formation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class AdminController extends Controller
{
    public function getFormateursEnAttente()
    {
        // Récupérer tous les formateurs avec le statut 'en_attente'
        $formateurs = User::where('role', 'formateur')
                          ->where('status', 'en_attente')
                          ->get();

        // Retourner les données à Inertia
        return Inertia::render('Formateurs/FormateurEnAttente', [
            'formateurs' => $formateurs
        ]);
    }
    // Fonction pour valider un formateur

    public function validerFormateur(Request $request, $id)
    {
        // Récupérer le formateur par son ID
        $formateur = User::findOrFail($id);
        
        // Mettre à jour son statut à 'valide'
        $formateur->status = 'valide';
        $formateur->save();
        
        // Envoyer l'email de félicitations
        $formateur->notify(new FormateurValidé());  // Envoyer la notification de félicitations
        
        // Retourner une réponse JSON avec un message de succès
        return response()->json([
            'message' => 'Formateur validé et email de félicitations envoyé.'
        ]);
    }
    
// Exemple dans la fonction de rejet
// Fonction pour rejeter un formateur
public function rejeterFormateur(Request $request, $id)
{
    // Récupérer le formateur par son ID
    $formateur = User::findOrFail($id);

    // Mettre à jour son statut à 'rejete'
    $formateur->status = 'rejete';
    $formateur->save();

    // Envoyer l'email de rejet
    $formateur->notify(new FormateurRejete());  // Envoi de la notification de rejet

    // Retourner une réponse avec un message de succès
    return response()->json(['message' => 'Formateur rejeté et email de rejet envoyé avec succès.']);
}

public function Listeformateur()
    {
        $formateurs = User::where('role', 'formateur')
        ->where('status', 'valide')
        ->get();
        return Inertia::render('Formateurs/ListeFormateur', [
            'formateurs' => $formateurs
        ]);
    }
public function deleteFormateur($id)
{
    $formateur = User::findOrFail($id);
    $formateur->delete();
    return response()->json([
        'message' => 'Formateur supprimé avec succès.'
    ]);
}

public function showFormateur($id)
{
    $formateur = User::findOrFail($id);
    return Inertia::render('Formateurs/Show', [ // Changer 'showFormateur' en un chemin correct
        'formateur' => $formateur
    ]);
}

public function editFormateur($id)
{
    $formateur = User::findOrFail($id);
    return Inertia::render('Formateurs/Edit', [
        'formateur' => $formateur
    ]);
}

public function updateFormateur(Request $request, $id)
{
    $formateur = User::findOrFail($id);

    // Validation des données
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'required|email|unique:users,email,' . $id,
        'speciality' => 'required|string|max:255',
        'description' => 'required|string|max:255',

    ]);

    // Mise à jour du formateur
    $formateur->update($validated);

    return redirect()->route('formateurs.index')->with('success', 'Formateur modifié avec succès.');
}
public function allUsers(Request $request)
{
       // Vérification du rôle directement dans le contrôleur
       if (Auth::user()->role !== 'admin') {
        return redirect()->route('dashboard')
            ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
    // Récupérer tous les utilisateurs avec pagination
    $query = User::query();
    
    // Filtrage par rôle si spécifié dans la requête
    if ($request->has('role') && $request->role) {
        $query->where('role', $request->role);
    }
    
    // Recherche par nom/prénom/email si spécifié
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
    
    // Tri des résultats
    $sortField = $request->input('sort_field', 'created_at');
    $sortDirection = $request->input('sort_direction', 'desc');
    $query->orderBy($sortField, $sortDirection);
    
    // Récupérer les utilisateurs avec pagination
    $users = $query->paginate(10);
    
    // Nombres d'utilisateurs par rôle (pour les statistiques)
    $userCounts = [
        'total' => User::count(),
        'user' => User::where('role', 'user')->count(),
        'formateur' => User::where('role', 'formateur')->count(),
        'startup' => User::where('role', 'startup')->count(),
    ];
    
    return Inertia::render('Admin/AllUsers', [
        'users' => $users,
        'filters' => [
            'role' => $request->role,
            'search' => $request->search,
            'sort_field' => $sortField,
            'sort_direction' => $sortDirection,
        ],
        'userCounts' => $userCounts
    ]);
}
public function showUser(User $user)
{
    return Inertia::render('Admin/UserShow', [
        'user' => $user
    ]);
}

// Afficher le formulaire d'édition
public function edit(User $user)
{
    return Inertia::render('Admin/UserEdit', [
        'user' => $user
    ]);
}

// Supprimer un utilisateur
public function destroy(User $user)
{
    $user->delete();
    
    return redirect()->route('admin.users')
        ->with('success', 'Utilisateur supprimé avec succès');
}
    /**
         * Get all payments with user and formation details
     */
    /**
     * Display a listing of the payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::with(['user', 'formation'])->get();
        return response()->json($payments);
    }

    /**
     * Get statistics about payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $stats = [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
        ];
        
        return response()->json($stats);
    }

    /**
     * Display the specified payment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::with(['user', 'formation'])->findOrFail($id);
        return response()->json($payment);
    }
    public function countUser()
    {
        $count = User::count();
        return response()->json(['count' => $count]);
    }
    public function countFormations()
{
    try {
        $count = Formation::where('est_valide', 'Validée')->count();
        return response()->json(['count' => $count]);
    } catch (\Exception $e) {
        // Log the error
        Log::error('Formation count error: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to count formations'], 500);
    }
}

public function getFormationsEnAttente()
{
    $formations = Formation::with('user:id,first_name,last_name,email')
                          ->with('user:id,first_name,last_name,email')
                          ->with('category:id,name')
                          ->select('id', 'titre', 'user_id', 'est_valide', 'created_at', 'category_id', 'image_formation')
                          ->get();
                          
    return Inertia::render('Formations/FormationEnAttente', [
        'formations' => $formations
    ]);
}
public function validerFormation(Request $request, $id) {
    // Récupérer la formation par son ID
    $formation = Formation::findOrFail($id);
    
    // Mettre à jour son statut à 'Validée'
    $formation->est_valide = "Validée";
    $formation->save();
    
    // Envoi d'un email de confirmation
    $this->envoyerEmailNotification($formation, 'Validee');
    
    return response()->json([
        'message' => 'Formation validée avec succès.'
    ]);
}

public function rejeterFormation(Request $request, $id) {
    // Récupérer la formation par son ID
    $formation = Formation::findOrFail($id);
    
    // Mettre à jour son statut à 'Rejetée'
    $formation->est_valide = "Rejetée";
    $formation->save();
    
    // Envoi d'un email de rejet
    $this->envoyerEmailNotification($formation, 'Rejetee');
    
    return response()->json([
        'message' => 'Formation rejetée avec succès.'
    ]);
}

/**
 * Envoie un email de notification concernant le statut de la formation
 * 
 * @param Formation $formation
 * @param string $statut
 * @return void
 */
private function envoyerEmailNotification(Formation $formation, $statut) {
    // Récupérer l'utilisateur associé à la formation
    $user = $formation->user;
    
    if ($user && $user->email) {
        Mail::send('emails.formation_status', [
            'formation' => $formation,
            'statut' => $statut
        ], function ($message) use ($user, $statut, $formation) {
            $message->to($user->email, $user->name)
                ->subject('Votre formation "' . $formation->titre . '" a été ' . strtolower($statut));
        });
    }
}
public function getFormationsEnAttente2()
{
    $formations = Formation::where('est_valide', 'EnAttente')
                          ->with('user:id,first_name,last_name,email')
                          ->with('category:id,name')
                          ->select('id', 'titre', 'user_id', 'est_valide', 'created_at', 'category_id')
                          ->get();
                          
    // Return JSON directly instead of Inertia response
    return response()->json($formations);
}
public function countFormateurEnAttente()
{
    try {
        $count = User::where('role', 'formateur')
                     ->where('status', 'en_attente')
                     ->count();
        return response()->json(['count' => $count]);
    } catch (\Exception $e) {
        // Log the error
        Log::error('Formateur count error: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to count formateurs'], 500);
    }
}
public function isAdmin()
    {
        return response()->json(['is_admin' => Auth::user()->role === 'admin']);
    }
}