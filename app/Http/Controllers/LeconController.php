<?php

namespace App\Http\Controllers;

use App\Models\Lecon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeconController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return all lessons (lecons)
        $lecons = Lecon::all();
        return response()->json($lecons);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the form view for creating a new lesson
        // If you're using Inertia.js or Blade, you can render the view accordingly.
        return Inertia::render('Lecons/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'nullable|string',
            'type' => 'nullable|array', // Example: ['video' => true, 'document' => false]
            'video_url' => 'nullable|mimes:mp4,mov,avi,wmv',
            'document_url' => 'nullable|mimes:pdf,doc,docx',
        ]);

        // Handle file uploads if needed
        $videoPath = $request->hasFile('video') ? $this->uploadFile($request->file('video'), 'videos') : null;
        $documentPath = $request->hasFile('document') ? $this->uploadFile($request->file('document'), 'documents') : null;

        // Create a new lesson
        $lecon = Lecon::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu ?? '',
            'type' => json_encode($request->type),
            'video_url' => $videoPath,
            'document_url' => $documentPath,
        ]);

        return response()->json($lecon, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lecon $lecon)
    {
        // Return the specific lesson details
        return response()->json($lecon);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lecon $lecon)
    {
        // Return the form view for editing the lesson
        return Inertia::render('Lecons/Edit', ['lecon' => $lecon]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lecon $lecon)
    {
        // Validate the incoming request
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'nullable|string',
            'type' => 'nullable|array',
            'video_url' => 'nullable|mimes:mp4,mov,avi,wmv',
            'document_url' => 'nullable|mimes:pdf,doc,docx',
        ]);

        // Handle file uploads if needed
        $videoPath = $request->hasFile('video') ? $this->uploadFile($request->file('video'), 'videos') : null;
        $documentPath = $request->hasFile('document') ? $this->uploadFile($request->file('document'), 'documents') : null;

        // Update the lesson data
        $lecon->update([
            'titre' => $request->titre,
            'contenu' => $request->contenu ?? '',
            'type' => json_encode($request->type),
            'video_url' => $videoPath,
            'document_url' => $documentPath,
        ]);

        return response()->json($lecon);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecon $lecon)
    {
        // Delete the lesson
        $lecon->delete();
        return response()->json(['message' => 'Leçon supprimée avec succès']);
    }

    /**
     * Helper method for uploading files.
     */
    private function uploadFile($file, $folder)
    {
        // Generate a unique file name and store it in the specified folder
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
        return $file->storeAs($folder, $fileName, 'public');
    }
}
