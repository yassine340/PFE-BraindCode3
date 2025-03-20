<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formations = Formation::select('id', 'titre', 'image_formation')->get();
        return Inertia::render('Formations/Index', [
            'formations' => $formations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Formations/Create');
    }

    /**
     * Store a newly created formation.
     */
    public function store(Request $request)
{
    $request->validate([
        'titre' => 'required|string|max:255',
        'prix' => 'required|numeric',
        'estcertifiante' => 'required|boolean',
        'image_formation' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'category_id' => 'required|exists:categories,id',
        'modules.*.titre' => 'required|string|max:255',
        'modules.*.description' => 'nullable|string',
        'modules.*.ordre' => 'required|integer',
        'modules.*.duree_estimee' => 'required|integer',
        'modules.*.lecons.*.titre' => 'required|string|max:255',
        'modules.*.lecons.*.contenu' => 'required|string',
        'videos.*.file' => 'nullable|mimes:mp4,mov,avi,wmv,mkv',
        'videos.*.titre' => 'nullable|string|max:255',
        'documents.*.file' => 'nullable|mimes:pdf,doc,docx,txt',
        'documents.*.titre' => 'nullable|string|max:255',
    ]);

    // Handle image upload
    if ($request->hasFile('image_formation')) {
        $imageName = time() . '_' . $request->file('image_formation')->getClientOriginalName();
        $imagePath = $request->file('image_formation')->storeAs('formations', $imageName, 'public');
    } else {
        $imagePath = null;
    }
    $request->validate([
        'category_id' => 'required|exists:categories,id',
    ]);

    Formation::create([
        'category_id' => $request->category_id,
    ]);
    // Create formation
    $formation = Formation::create([
        'titre' => $request->titre,
        'prix' => $request->prix,
        'estcertifiante' => $request->estcertifiante,
        'image_formation' => $imagePath,
        'category_id' => $request->category_id,
    ]);

    // Create modules and lessons
    if ($request->has('modules')) {
        foreach ($request->modules as $moduleData) {
            // Create module
            $module = $formation->modules()->create([
                'titre' => $moduleData['titre'],
                'description' => $moduleData['description'] ?? null,
                'ordre' => $moduleData['ordre'],
                'duree_estimee' => $moduleData['duree_estimee'],
            ]);

            // Create lessons for each module
            if (isset($moduleData['lecons'])) {
                foreach ($moduleData['lecons'] as $leconData) {
                    $lecon = $module->lecons()->create([
                        'titre' => $leconData['titre'],
                        'contenu' => $leconData['contenu'],
                    ]);

                    // Handle video upload for the lesson
                    if (isset($leconData['videos'])) {
                        foreach ($leconData['videos'] as $videoData) {
                            if (isset($videoData['file'])) {
                                $videoName = time() . '_' . $videoData['file']->getClientOriginalName();
                                $videoPath = $videoData['file']->storeAs('videos', $videoName, 'public');

                                $lecon->videos()->create([
                                    'titre' => $videoData['titre'] ?? 'Sans titre',
                                    'url' => Storage::url($videoPath),
                                ]);
                            }
                        }
                    }

                    // Handle document upload for the lesson
                    if (isset($leconData['documents'])) {
                        foreach ($leconData['documents'] as $documentData) {
                            if (isset($documentData['file'])) {
                                $documentName = time() . '_' . $documentData['file']->getClientOriginalName();
                                $documentPath = $documentData['file']->storeAs('documents', $documentName, 'public');

                                $lecon->documents()->create([
                                    'titre' => $documentData['titre'] ?? 'Sans titre',
                                    'url' => Storage::url($documentPath),
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    return Inertia::render('Formations/Create', [
        'message' => 'Formation créée avec succès',
        'formation' => $formation
    ]);
}

public function show($id)
{
    $formation = Formation::with([
        'modules.lecons.videos',
        'modules.lecons.documents',
        'category'
    ])->findOrFail($id);

    return Inertia::render('Formations/Show', [
        'formation' => $formation
    ]);
}
public function destroy($id)
{
    $formation = Formation::findOrFail($id);
    
    // Supprimer les fichiers associés (image, vidéos, documents)
    if ($formation->image_formation) {
        Storage::delete('public/' . $formation->image_formation);
    }
    
    foreach ($formation->modules as $module) {
        foreach ($module->lecons as $lecon) {
            foreach ($lecon->videos as $video) {
                Storage::delete('public/' . $video->file);
            }
            foreach ($lecon->documents as $document) {
                Storage::delete('public/' . $document->file);
            }
        }
    }

    // Supprimer la formation avec ses relations
    $formation->delete();

    return redirect()->route('formations.index')->with('success', 'Formation supprimée avec succès.');
}
public function edit($id)
{
    $formation = Formation::with(['modules.lecons'])->findOrFail($id);

    return Inertia::render('Formations/Edit', [
        'formation' => $formation
    ]);
}
public function update(Request $request, $id)
{
    $formation = Formation::findOrFail($id);

    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'prix' => 'required|numeric',
        'estcertifiante' => 'required|boolean',
        'image_formation' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'category_id' => 'required|exists:categories,id',
    ]);

    // Gérer l'upload de l'image si elle a été modifiée
    if ($request->hasFile('image_formation')) {
        $path = $request->file('image_formation')->store('formations', 'public');
        $validated['image_formation'] = $path;
    }

    // Mise à jour des données
    $formation->update($validated);

    return redirect()->route('formations.show', $formation->id)
        ->with('success', 'Formation mise à jour avec succès.');
}


}
