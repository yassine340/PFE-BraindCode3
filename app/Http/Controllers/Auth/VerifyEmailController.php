<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Vérifier si l'utilisateur a déjà vérifié son email
        if ($request->user()->hasVerifiedEmail()) {
            // Si l'email est déjà vérifié, redirigez vers le tableau de bord approprié
            if ($request->user()->role === 'formateur') {
                return redirect()->route('DashboardFormateur', ['verified' => 1]);  // Ajouter le paramètre 'verified=1'
            } elseif ($request->user()->role === 'startup' || $request->user()->role === 'user') {
                return redirect()->route('dashboard', ['verified' => 1]);  // Ajouter le paramètre 'verified=1'
            }
        }

        // Marquer l'email de l'utilisateur comme vérifié
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));  // Déclencher l'événement de vérification
        }

        // Redirigez l'utilisateur après la vérification de l'email
        if ($request->user()->role === 'formateur') {
            return redirect()->route('DashboardFormateur', ['verified' => 1]);  // Ajouter le paramètre 'verified=1'
        } elseif ($request->user()->role === 'startup' || $request->user()->role === 'user') {
            return redirect()->route('dashboard', ['verified' => 1]);  // Ajouter le paramètre 'verified=1'
        }

        // Par défaut, redirigez vers une page d'accueil ou autre page de votre choix
        return redirect('/');
    }
}


