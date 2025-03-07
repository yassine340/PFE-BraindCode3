<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche la vue de connexion.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Gère la requête d'authentification entrante.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authentifier l'utilisateur
        $request->authenticate();
    
        // Régénérer la session pour des raisons de sécurité
        $request->session()->regenerate();
    
        // Obtenir l'utilisateur actuellement authentifié
        $user = Auth::user();
    
        // Vérifier si l'utilisateur est un formateur avec le statut "en_attente"
        if ($user->role === 'formateur' && $user->status === 'en_attente') {
            // Déconnecter immédiatement l'utilisateur
            Auth::logout();
    
            // Invalider la session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            // Rediriger vers la page de connexion avec un message d'erreur
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte est en attente de validation par l\'administrateur.',
            ]);
        }
    
        // Vérifier le rôle de l'utilisateur et rediriger en conséquence
        if ($user->role === 'admin') {
            return redirect()->route('dashboardAdmin'); // Redirige vers le dashboard des administrateurs
        } elseif ($user->role === 'formateur') {
            return redirect()->route('DashboardFormateur'); // Redirige vers le dashboard des formateurs
        }
    
        // Par défaut, rediriger vers le dashboard général
        return redirect()->route('dashboard');
    }
    

    /**
     * Détruire la session authentifiée.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Déconnexion de l'utilisateur
        Auth::guard('web')->logout();

        // Invalider la session pour des raisons de sécurité
        $request->session()->invalidate();

        // Régénérer le token CSRF pour des raisons de sécurité
        $request->session()->regenerateToken();

        // Rediriger vers la page d'accueil après la déconnexion
        return redirect('/');
    }
}
