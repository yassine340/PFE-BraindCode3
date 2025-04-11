<?php
namespace App\Http\Controllers;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Notifications\FormateurValidé;
use App\Notifications\FormateurRejete;
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

/**
 * Afficher tous les utilisateurs de la plateforme
 */
public function allUsers(Request $request)
{
       // Vérification du rôle directement dans le contrôleur
       if (auth()->user()->role !== 'admin') {
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
    $users = $query->paginate(10)->withQueryString();
    
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
public function show(User $user)
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
}
