<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $data = $request->validated();
            
            // Gestion de l'upload d'image de profil avec S3
            if ($request->hasFile('profile_image')) {
                // Supprimer l'ancienne image si elle existe
                if ($user->profile_image) {
                    try {
                        // Vérifier si l'image est stockée sur S3
                        if (str_starts_with($user->profile_image, 'profile_images/')) {
                            // Supprimer de S3
                            Storage::disk('s3')->delete($user->profile_image);
                            \Log::info('Ancienne image S3 supprimée', ['path' => $user->profile_image]);
                        } else {
                            // Supprimer du stockage local (pour la compatibilité avec les anciennes images)
                            $localPath = str_replace('/storage/', '', $user->profile_image);
                            if (Storage::disk('public')->exists($localPath)) {
                                Storage::disk('public')->delete($localPath);
                                \Log::info('Ancienne image locale supprimée', ['path' => $localPath]);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Erreur lors de la suppression de l\'ancienne image: ' . $e->getMessage());
                        // Continuer même si la suppression échoue
                    }
                }
                
                // Générer un nom de fichier unique et sécurisé
                $originalName = $request->file('profile_image')->getClientOriginalName();
                $safeFileName = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $originalName);
                $imageName = time() . '_' . $safeFileName;
                
                // Stocker la nouvelle image sur S3
                try {
                    $imagePath = $request->file('profile_image')->storeAs(
                        'profile_images', 
                        $imageName, 
                        's3'
                    );
                    
                    if (!$imagePath) {
                        throw new \Exception("Échec du stockage sur S3");
                    }
                    
                    // Stocker le chemin S3 dans la base de données
                    $data['profile_image'] = $imagePath;
                    
                    \Log::info('Image de profil téléchargée sur S3', [
                        'user_id' => $user->id,
                        'path' => $imagePath
                    ]);
                } catch (\Exception $e) {
                    // Fallback: Stockage local en cas d'échec de S3
                    \Log::error('Erreur lors du stockage sur S3, utilisation du stockage local: ' . $e->getMessage());
                    
                    $localPath = $request->file('profile_image')->store('public/profile_images');
                    $data['profile_image'] = str_replace('public/', '', $localPath);
                    
                    \Log::info('Image stockée localement comme fallback', [
                        'user_id' => $user->id,
                        'path' => $data['profile_image']
                    ]);
                }
            }
            
            // Mise à jour des données utilisateur
            $user->fill($data);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du profil', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Redirect::route('profile.edit')
                ->with('error', 'Une erreur est survenue lors de la mise à jour de votre profil');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Supprimer l'image de profil si elle existe
        if ($user->profile_image) {
            try {
                // Vérifier si l'image est stockée sur S3
                if (str_starts_with($user->profile_image, 'profile_images/')) {
                    // Supprimer de S3
                    Storage::disk('s3')->delete($user->profile_image);
                } else {
                    // Supprimer du stockage local
                    Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile_image));
                }
            } catch (\Exception $e) {
                \Log::warning('Erreur lors de la suppression de l\'image lors de la suppression du compte: ' . $e->getMessage());
                // Continuer même si la suppression échoue
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}