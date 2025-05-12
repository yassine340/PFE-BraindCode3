<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewContactMessage;

class ContactController extends Controller
{
    /**
     * Display the contact form.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('ContactPage');
    }

    /**
     * Store a newly created contact message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Create the contact record
        $contact = Contact::create([
            'name' => $validated['name'],
            'first_name' => $validated['firstName'], // Note the transformation from camelCase to snake_case
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
        ]);

        // Send email to admin
        Mail::to(config('mail.admin_email', 'admin@example.com'))
            ->send(new NewContactMessage($contact));

        // Return a JSON response for API requests
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Votre message a été envoyé avec succès!'], 200);
        }

        // For normal requests, redirect back with success message and alert
        return redirect()->route('contact')
            ->with('success', 'Votre message a été envoyé avec succès!')
            ->with('alert', true); // Add alert flag
    }
}