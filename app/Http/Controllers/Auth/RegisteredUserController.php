<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Afficher la vue d'inscription.
     */
    public function create(): Response
    {
        // Retourne la vue d'inscription via Inertia
        return Inertia::render('Auth/Register');
    }

    /**
     * Gérer une requête d'inscription.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'         => 'nullable|string|max:20',
            'role'          => 'required|string|in:user,formateur,startup',
            'startup_name'  => 'nullable|string|max:255',
            'code_fiscal'   => 'nullable|string|max:50|unique:users,code_fiscal',
            'startup_email' => 'nullable|string|email|max:255|unique:users,startup_email',
            'startup_phone' => 'nullable|string|max:20',
            'speciality'    => 'nullable|string|max:255',
            'description'   => 'nullable|string',
            'cv_file'       => $request->role === 'formateur' ? 'required|file|mimes:pdf,doc,docx|max:5120' : 'nullable',
        ]);
        
        // Initialiser le chemin du CV à null
        $cvFilePath = null;
        
        // Traiter l'upload du CV pour les formateurs
        if ($request->role === 'formateur' && $request->hasFile('cv_file')) {
            $cvFilePath = $request->file('cv_file')->storeAs(
                'cv_files',
                time() . '_' . $request->file('cv_file')->getClientOriginalName(),
                'public'
            );
            
            $cvFilePath = '/storage/' . $cvFilePath;
        }
    
        $user = User::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'role'          => $request->role, 
            'startup_name'  => $request->startup_name,
            'code_fiscal'   => $request->code_fiscal,
            'startup_email' => $request->startup_email,
            'startup_phone' => $request->startup_phone,
            'speciality'    => $request->speciality,
            'description'   => $request->description,
            'status'        => $request->role === 'formateur' ? 'en_attente' : null,
            'cv_file'       => $cvFilePath,
        ]);
    
        if ($user->role !== 'formateur') {
            event(new Registered($user));
            Auth::login($user);
    
            // ✅ Redirection compatible avec Inertia.js
            return Inertia::location(route('dashboard'));
        }
    
        $request->session()->flash('message', 'Merci pour votre demande d\'inscription. Vous recevrez un email ou un SMS de réponse.');
    
        return Inertia::render('Auth/Login', [
            'message' => session('message')
        ]);
    }
}