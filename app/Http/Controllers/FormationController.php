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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'titre' => 'required|string|max:255',
                'prix' => 'required|numeric',
                'estcertifiante' => 'required|boolean',
                'image_formation' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'category_id' => 'required|exists:categories,id',
                'modules.*.titre' => 'required|string|max:255',
                'modules.*.description' => 'nullable|string',
                'modules.*.ordre' => 'required|integer',
                'modules.*.duree_estimee' => 'required|integer',
                'modules.*.lecons.*.titre' => 'required|string|max:255',
                'modules.*.lecons.*.contenu' => 'required|string',
                'modules.*.lecons.*.videos.*.file' => 'nullable|mimes:mp4,mov,avi,wmv,flv,mkv',
                'modules.*.lecons.*.videos.*.titre' => 'nullable|string|max:255',
                'modules.*.lecons.*.documents.*.file' => 'nullable|mimes:pdf,doc,docx,txt',
                'modules.*.lecons.*.documents.*.titre' => 'nullable|string|max:255',
                'modules.*.lecons.*.quiz.titre' => 'nullable|string|max:255',
                'modules.*.lecons.*.quiz.noteFinale' => 'nullable|integer',
                'modules.*.lecons.*.quiz.dureeMaximale' => 'nullable|integer',
                'modules.*.lecons.*.quiz.questions.*.contenu' => 'nullable|string',
                'modules.*.lecons.*.quiz.questions.*.reponses.*.contenu' => 'nullable|string',
                'modules.*.lecons.*.quiz.questions.*.reponses.*.est_correcte' => 'nullable|boolean',
            ]);

            // Handle image upload
            $imagePath = $request->hasFile('image_formation') 
                ? $request->file('image_formation')->storeAs('formations', time() . '_' . $request->file('image_formation')->getClientOriginalName(), 'public') 
                : null;
            
            // Create formation
            $formation = Formation::create([
                'titre' => $request->titre,
                'prix' => $request->prix,
                'estcertifiante' => $request->estcertifiante,
                'image_formation' => $imagePath,
                'category_id' => $request->category_id,
            ]);

            // Create modules, lessons, quizzes, questions, and answers
            foreach ($request->modules as $moduleData) {
                $module = $formation->modules()->create([
                    'titre' => $moduleData['titre'],
                    'description' => $moduleData['description'] ?? null,
                    'ordre' => $moduleData['ordre'],
                    'duree_estimee' => $moduleData['duree_estimee'],
                ]);

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

                    // Create quiz if provided
                    if (isset($leconData['quiz'])) {
                        $quiz = $lecon->quiz()->create([
                            'titre' => $leconData['quiz']['titre'],
                            'noteFinale' => $leconData['quiz']['noteFinale'],
                            'dureeMaximale' => $leconData['quiz']['dureeMaximale'],
                        ]);

                        // Create questions for the quiz
                        if (isset($leconData['quiz']['questions'])) {
                            foreach ($leconData['quiz']['questions'] as $questionData) {
                                $question = $quiz->questions()->create([
                                    'contenu' => $questionData['contenu'],
                                ]);

                                // Create answers for the question
                                if (isset($questionData['reponses'])) {
                                    foreach ($questionData['reponses'] as $reponseData) {
                                        $question->reponses()->create([
                                            'contenu' => $reponseData['contenu'],
                                            'est_correcte' => $reponseData['est_correcte'],
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return response()->json([
                'message' => 'Formation créée avec succès avec ses modules, leçons, vidéos, documents et quiz',
                'formation' => $formation,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $formation = Formation::with([
            'modules.lecons.videos',
            'modules.lecons.documents',
            'modules.lecons.quiz.questions.reponses', 
            'category'
        ])->findOrFail($id);

        $currentModuleIndex = 0; 
        $currentLeconIndex = 0; 

        $modules = $formation->modules;

        $currentModule = $modules[$currentModuleIndex] ?? null;
        $currentLecon = $currentModule->lecons[$currentLeconIndex] ?? null;

        return Inertia::render('Formations/Show', [
            'formation' => $formation,
            'currentModule' => $currentModule,
            'currentLecon' => $currentLecon,
            'currentModuleIndex' => $currentModuleIndex,
            'currentLeconIndex' => $currentLeconIndex,
            'totalModules' => count($modules),
            'totalLecons' => $currentModule ? count($currentModule->lecons) : 0,
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