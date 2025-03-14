<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Inertia\Inertia; // N'oublie pas d'importer Inertia
use Illuminate\Support\Facades\Storage;
class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
       // 📌 Fonction pour afficher toutes les formations
       public function index()
       {
           $formations = Formation::select('id', 'titre', 'image_formation')->get();
           
           return Inertia::render('Formations/Index', [
               'formations' => $formations
           ]);
       }
       // 📌 Fonction pour afficher les vidéos d'une formation
    public function show($id)
    {
        $formation = Formation::with('videos')->findOrFail($id);

        return Inertia::render('Formations/Show', [
            'formation' => $formation
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retourne la vue de création de formation
        return Inertia::render('Formations/Create');
    }

    /**
     * Créer une nouvelle formation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'estcertifiante' => 'required|boolean',
            'image_formation' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'videos.*.file' => 'nullable|mimes:mp4,mov,avi,wmv', // Validation pour plusieurs vidéos
            'videos.*.titre' => 'nullable|string|max:255',
            'documents.*.file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5000', // max 5MB for documents
            'documents.*.titre' => 'nullable|string|max:255',
        ]);
    
        if ($request->hasFile('image_formation')) {
            $imageName = time() . '_' . $request->file('image_formation')->getClientOriginalName();
            $imagePath = $request->file('image_formation')->storeAs('formations', $imageName, 'public');
        } else {
            $imagePath = null;
        }
        
        // Création de la formation
        $formation = Formation::create([
            'titre' => $request->titre,
            'prix' => $request->prix,
            'estcertifiante' => $request->estcertifiante,
            'image_formation' => $imagePath,
        ]);
    
        if ($request->has('videos')) {
            foreach ($request->videos as $videoData) {
                if (isset($videoData['file'])) {
                    // Récupérer le nom original et ajouter un timestamp pour éviter les doublons
                    $videoName = time() . '_' . $videoData['file']->getClientOriginalName();
                    $videoPath = $videoData['file']->storeAs('videos', $videoName, 'public');
        
                    $formation->videos()->create([
                        'titre' => $videoData['titre'] ?? 'Sans titre',
                        'url' => Storage::url($videoPath),
                    ]);
                }
            }
        }
        if($request->has('documents')) {
            foreach ($request->documents as $documentData) {
                if (isset($documentData['file'])) {
                    // Récupérer le nom original et ajouter un timestamp pour éviter les doublons
                    $documentName = time() . '_' . $documentData['file']->getClientOriginalName();
                    $documentPath = $documentData['file']->storeAs('documents', $documentName, 'public');
        
                    $formation->documents()->create([
                        'titre' => $documentData['titre'] ?? 'Sans titre',
                        'url' => Storage::url($documentPath),
                    ]);
                }
            }
        }
    
        return Inertia::render('Create', [
            'message' => 'Formation créée avec succès',
            'formation' => $formation
        ]);
            }
    

private function uploadVideo($video)
{
    $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $video->getClientOriginalName());

    // Stocker la vidéo
    $path = $video->storeAs('videos', $fileName, 'public');

    return [
        'message' => 'Vidéo uploadée avec succès !',
        'url' => Storage::url($path)
    ];
}

public function uploafdocumet($document)
{
    $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $document->getClientOriginalName());
    $path = $document->storeAs('documents', $fileName, 'public');
    return [
        'message' => 'Document uploadé avec succès !',
        'url' => Storage::url($path)
    ];
}
    
    

    /**
     * Display the specified resource.
     */
   /* public function show(Formation $formation)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation)
    {
        //
    }
}