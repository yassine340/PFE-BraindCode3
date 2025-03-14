<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


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
        // Return the view for creating a formation
        return Inertia::render('Formations/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'titre' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'estcertifiante' => 'required|boolean',
            'image_formation' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'videos.*.file' => 'nullable|mimes:mp4,mov,avi,wmv|max:10000', // max 10MB for videos
            'videos.*.titre' => 'nullable|string|max:255',
            'documents.*.file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5000', // max 5MB for documents
            'documents.*.titre' => 'nullable|string|max:255',
        ]);

        // ✅ Handle Image Upload
        if ($request->hasFile('image_formation')) {
            $imageName = time() . '_' . $request->file('image_formation')->getClientOriginalName();
            $imagePath = $request->file('image_formation')->storeAs('formations', $imageName, 'public');
        } else {
            $imagePath = null; // If no image is uploaded
        }

        // ✅ Create Formation
        $formation = Formation::create([
            'titre' => $request->titre,
            'prix' => $request->prix,
            'estcertifiante' => $request->estcertifiante,
            'image_formation' => $imagePath ? Storage::url($imagePath) : null, // Use Storage::url for public URL
        ]);

        // ✅ Handle Video Uploads
        if ($request->has('videos')) {
            foreach ($request->videos as $videoData) {
                if (isset($videoData['file'])) {
                    $videoUpload = $this->uploadVideo($videoData['file']);
                    $formation->videos()->create([
                        'titre' => $videoData['titre'] ?? 'Sans titre',
                        'url' => $videoUpload['url'],
                    ]);
                }
            }
        }

        // ✅ Handle Document Uploads
        if ($request->has('documents')) {
            foreach ($request->documents as $documentData) {
                if (isset($documentData['file'])) {
                    $documentName = time() . '_' . $documentData['file']->getClientOriginalName();
                    $documentPath = $documentData['file']->storeAs('documents', $documentName, 'public');

                    $formation->documents()->create([
                        'titre' => $documentData['titre'] ?? 'Sans titre',
                        'url' => Storage::url($documentPath),
                    ]);
                }
            }
        }

        return Inertia::render('Formations/Create', [
            'message' => 'Formation créée avec succès',
            'formation' => $formation
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        // Handle editing logic here
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formation $formation)
    {
        // Handle update logic here
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formation $formation)
    {
        // Handle destroy logic here
    }

    /**
     * Handle video upload.
     */
    private function uploadVideo($video)
    {
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $video->getClientOriginalName());

        // Store the video
        $path = $video->storeAs('videos', $fileName, 'public');

        return [
            'message' => 'Vidéo uploadée avec succès !',
            'url' => Storage::url($path)
        ];
    }
    /**
 * Display the specified resource.
 */
public function show($id)
    {
        // Log the id to debug
        Log::info('Formation ID: ' . $id);
        
        $formation = Formation::with(['videos', 'documents'])->findOrFail($id);

        return Inertia::render('Formations/Show', [
            'formation' => $formation
        ]);
    }

}
