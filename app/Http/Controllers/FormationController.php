<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\FormationUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Formation::select('id', 'titre', 'image_formation', 'est_valide', 'est_publiee', 'user_id','prix', 'estcertifiante', 'category_id', 'created_at','description', 'duree')
                           ->with('user:id,first_name,last_name,email') // Chargement de l'utilisateur (formateur)
                           ->withCount('modules')
                           ->where('est_valide', 'Validée') // Par défaut, ne montrer que les formations validées
                           ->where('est_publiee', 1);
        
        $formations = $query->get();
        
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
        $request->validate([
            'titre' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'estcertifiante' => 'required|boolean',
            'description' => 'nullable|string',
            'duree' => 'nullable|string',
            'est_publiee' => 'nullable|boolean',
            'image_formation' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'category_id' => 'required|exists:categories,id',
            'modules.*.titre' => 'required|string|max:255',
            'modules.*.description' => 'nullable|string',
            'modules.*.ordre' => 'required|integer',
            'modules.*.duree_estimee' => 'required|integer',
            'modules.*.lecons.*.titre' => 'required|string|max:255',
            'modules.*.lecons.*.contenu' => 'required|string',
            'modules.*.lecons.*.videos.*.file' => 'nullable|mimes:mp4,mov,avi,wmv',
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

        // Handle image upload - STOCKAGE SUR S3 (au lieu de local)
        $imagePath = null;
        if ($request->hasFile('image_formation')) {
            $imageName = time() . '_' . $request->file('image_formation')->getClientOriginalName();
            // Stocker l'image sur S3 dans le dossier 'formations/images'
            $imagePath = $request->file('image_formation')->storeAs('formations/images', $imageName, 's3');
        }

        // Create formation
        $formation = Formation::create([
            'titre' => $request->titre,
            'prix' => $request->prix,
            'estcertifiante' => $request->estcertifiante,
            'description' => $request->description,
            'duree' => $request->duree,
            'est_publiee' => $request->est_publiee,
            'image_formation' => $imagePath,  // Chemin de l'image sur S3
            'category_id' => $request->category_id,
            'user_id' => auth()->id(), // ID de l'utilisateur connecté (formateur)
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

                // Handle video upload for the lesson - CLOUD S3
                if (isset($leconData['videos'])) {
                    foreach ($leconData['videos'] as $videoData) {
                        if (isset($videoData['file'])) {
                            $videoName = time() . '_' . $videoData['file']->getClientOriginalName();
                            $videoPath = $videoData['file']->storeAs('videos', $videoName, 's3');

                            $lecon->videos()->create([
                                'titre' => $videoData['titre'] ?? 'Sans titre',
                                'url' => $videoPath,  // Chemin relatif sur S3
                            ]);
                        }
                    }
                }

                // Handle document upload for the lesson - CLOUD S3
                if (isset($leconData['documents'])) {
                    foreach ($leconData['documents'] as $documentData) {
                        if (isset($documentData['file'])) {
                            $documentName = time() . '_' . $documentData['file']->getClientOriginalName();
                            $documentPath = $documentData['file']->storeAs('documents', $documentName, 's3');

                            $lecon->documents()->create([
                                'titre' => $documentData['titre'] ?? 'Sans titre',
                                'url' => $documentPath,  // Chemin relatif sur S3
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

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $formation = Formation::with([
        'modules.lecons.videos',
        'modules.lecons.documents',
        'modules.lecons.quiz.questions.reponses',
        'category',
        'user:id,first_name,last_name,email'
    ])->findOrFail($id);

    // Réglages par défaut pour la navigation
    $currentModuleIndex = 0;
    $currentLeconIndex = 0;
    $modules = $formation->modules;
    $currentModule = $modules[$currentModuleIndex] ?? null;
    $currentLecon = $currentModule->lecons[$currentLeconIndex] ?? null;

    // Vérification des données d'accès et d'expiration
    $isAchetee = false;
    $dateExpiration = null;
    $tempsRestantTexte = null;
    $compteurTemps = null;

    if (auth()->check()) {
        // Ajoutez un log pour déboguer
        \Log::info('Vérification de l\'achat pour user_id=' . auth()->id() . ' et formation_id=' . $formation->id);
        
        $formationUser = FormationUser::where('user_id', auth()->id())
                                         ->where('formation_id', $formation->id)
                                         ->first();
        
        if ($formationUser) {
            \Log::info('Formation achetée trouvée: ', [
                'date_achat' => $formationUser->date_achat,
                'date_expiration' => $formationUser->date_expiration
            ]);
            
            $isAchetee = true;
            $dateExpiration = $formationUser->date_expiration->toISOString();
            $tempsRestantTexte = $formationUser->getTempsRestant();
            $compteurTemps = $formationUser->getCompteurTemps();
            
            \Log::info('Données calculées: ', [
                'tempsRestantTexte' => $tempsRestantTexte,
                'compteurTemps' => $compteurTemps
            ]);
        } else {
            \Log::info('Aucun achat trouvé pour cette formation');
        }
    }

    return Inertia::render('Formations/Show', [
        'formation' => $formation,
        'currentModule' => $currentModule,
        'currentLecon' => $currentLecon,
        'currentModuleIndex' => $currentModuleIndex,
        'currentLeconIndex' => $currentLeconIndex,
        'totalModules' => count($modules),
        'totalLecons' => $currentModule ? count($currentModule->lecons) : 0,
        'isOwner' => $formation->user_id === auth()->id(),
        'isAchetee' => $isAchetee,
        'dateExpiration' => $dateExpiration,
        'tempsRestantTexte' => $tempsRestantTexte,
        'compteurTemps' => $compteurTemps,
    ]);
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $formation = Formation::with(['modules.lecons'])->findOrFail($id);
        return Inertia::render('Formations/Edit', [
            'formation' => $formation
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
  
    public function update(Request $request, $id)
    {
        try {
            $formation = Formation::findOrFail($id);
            
            $request->validate([
                'titre' => 'required|string|max:255',
                'prix' => 'required|numeric',
                'estcertifiante' => 'required|boolean',
                'description' => 'nullable|string',
                'duree' => 'nullable|string',
                'est_publiee' => 'nullable|boolean',
                'image_formation' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'category_id' => 'required|exists:categories,id',
                'modules.*.id' => 'nullable|exists:modules,id',
                'modules.*.titre' => 'required|string|max:255',
                'modules.*.description' => 'nullable|string',
                'modules.*.ordre' => 'required|integer',
                'modules.*.duree_estimee' => 'required|integer',
                'modules.*.lecons.*.id' => 'nullable|exists:lecons,id',
                'modules.*.lecons.*.titre' => 'required|string|max:255',
                'modules.*.lecons.*.contenu' => 'required|string',
                'modules.*.lecons.*.videos.*.id' => 'nullable|exists:videos,id',
                'modules.*.lecons.*.videos.*.file' => 'nullable|mimes:mp4,mov,avi,wmv',
                'modules.*.lecons.*.videos.*.titre' => 'nullable|string|max:255',
                'modules.*.lecons.*.documents.*.id' => 'nullable|exists:documents,id',
                'modules.*.lecons.*.documents.*.file' => 'nullable|mimes:pdf,doc,docx,txt',
                'modules.*.lecons.*.documents.*.titre' => 'nullable|string|max:255',
                'modules.*.lecons.*.quiz.id' => 'nullable|exists:quizzes,id',
                'modules.*.lecons.*.quiz.titre' => 'nullable|string|max:255',
                'modules.*.lecons.*.quiz.noteFinale' => 'nullable|integer',
                'modules.*.lecons.*.quiz.dureeMaximale' => 'nullable|integer',
                'modules.*.lecons.*.quiz.questions.*.id' => 'nullable|exists:questions,id',
                'modules.*.lecons.*.quiz.questions.*.contenu' => 'nullable|string',
                'modules.*.lecons.*.quiz.questions.*.reponses.*.id' => 'nullable|exists:reponses,id',
                'modules.*.lecons.*.quiz.questions.*.reponses.*.contenu' => 'nullable|string',
                'modules.*.lecons.*.quiz.questions.*.reponses.*.est_correcte' => 'nullable|boolean',
            ]);
    
            // Handle image upload if new image is provided - VERSION S3
            if ($request->hasFile('image_formation')) {
                // Delete old image if exists
                if ($formation->image_formation) {
                    // Vérifier si c'est un chemin S3
                    if (str_starts_with($formation->image_formation, 'formations/images')) {
                        Storage::disk('s3')->delete($formation->image_formation);
                    } else {
                        // Fallback pour les anciennes images stockées localement
                        Storage::delete('public/' . $formation->image_formation);
                    }
                }
                
                // Stocker la nouvelle image sur S3
                $imageName = time() . '_' . $request->file('image_formation')->getClientOriginalName();
                $imagePath = $request->file('image_formation')->storeAs('formations/images', $imageName, 's3');
                $formation->image_formation = $imagePath;
            }
    
            // Update formation basic details
            $formation->update([
                'titre' => $request->titre,
                'prix' => $request->prix,
                'estcertifiante' => $request->estcertifiante,
                'description' => $request->description,
                'duree' => $request->duree,
                'est_publiee' => $request->est_publiee,
                'category_id' => $request->category_id,
            ]);
    
            // Get existing module IDs for this formation
            $existingModuleIds = $formation->modules()->pluck('id')->toArray();
            $updatedModuleIds = [];
    
            // Update or create modules
            foreach ($request->modules as $moduleData) {
                if (isset($moduleData['id'])) {
                    // Update existing module
                    $module = $formation->modules()->findOrFail($moduleData['id']);
                    $module->update([
                        'titre' => $moduleData['titre'],
                        'description' => $moduleData['description'] ?? null,
                        'ordre' => $moduleData['ordre'],
                        'duree_estimee' => $moduleData['duree_estimee'],
                    ]);
                    $updatedModuleIds[] = $module->id;
                } else {
                    // Create new module
                    $module = $formation->modules()->create([
                        'titre' => $moduleData['titre'],
                        'description' => $moduleData['description'] ?? null,
                        'ordre' => $moduleData['ordre'],
                        'duree_estimee' => $moduleData['duree_estimee'],
                    ]);
                    $updatedModuleIds[] = $module->id;
                }
    
                // Get existing lesson IDs for this module
                $existingLeconIds = $module->lecons()->pluck('id')->toArray();
                $updatedLeconIds = [];
    
                // Update or create lessons
                foreach ($moduleData['lecons'] as $leconData) {
                    if (isset($leconData['id'])) {
                        // Update existing lesson
                        $lecon = $module->lecons()->findOrFail($leconData['id']);
                        $lecon->update([
                            'titre' => $leconData['titre'],
                            'contenu' => $leconData['contenu'],
                        ]);
                        $updatedLeconIds[] = $lecon->id;
                    } else {
                        // Create new lesson
                        $lecon = $module->lecons()->create([
                            'titre' => $leconData['titre'],
                            'contenu' => $leconData['contenu'],
                        ]);
                        $updatedLeconIds[] = $lecon->id;
                    }
    
                    // Handle videos - VERSION S3
                    if (isset($leconData['videos'])) {
                        $existingVideoIds = $lecon->videos()->pluck('id')->toArray();
                        $updatedVideoIds = [];
    
                        foreach ($leconData['videos'] as $videoData) {
                            if (isset($videoData['id'])) {
                                // Update existing video
                                $video = $lecon->videos()->findOrFail($videoData['id']);
                                $video->update([
                                    'titre' => $videoData['titre'] ?? 'Sans titre',
                                ]);
                                $updatedVideoIds[] = $video->id;
    
                                // Update file if needed
                                if (isset($videoData['file'])) {
                                    // Delete old file (S3 ou local)
                                    if (str_starts_with($video->url, 'videos/')) {
                                        Storage::disk('s3')->delete($video->url);
                                    } else {
                                        Storage::delete(str_replace('/storage/', 'public/', $video->url));
                                    }
                                    
                                    // Upload new file to S3
                                    $videoName = time() . '_' . $videoData['file']->getClientOriginalName();
                                    $videoPath = $videoData['file']->storeAs('videos', $videoName, 's3');
                                    $video->url = $videoPath;
                                    $video->save();
                                }
                            } else if (isset($videoData['file'])) {
                                // Create new video on S3
                                $videoName = time() . '_' . $videoData['file']->getClientOriginalName();
                                $videoPath = $videoData['file']->storeAs('videos', $videoName, 's3');
                                
                                $video = $lecon->videos()->create([
                                    'titre' => $videoData['titre'] ?? 'Sans titre',
                                    'url' => $videoPath,
                                ]);
                                $updatedVideoIds[] = $video->id;
                            }
                        }
    
                        // Delete videos that were not updated or created
                        foreach ($existingVideoIds as $videoId) {
                            if (!in_array($videoId, $updatedVideoIds)) {
                                $video = $lecon->videos()->find($videoId);
                                if ($video) {
                                    // Supprimer de S3 ou du stockage local
                                    if (str_starts_with($video->url, 'videos/')) {
                                        Storage::disk('s3')->delete($video->url);
                                    } else {
                                        Storage::delete(str_replace('/storage/', 'public/', $video->url));
                                    }
                                    $video->delete();
                                }
                            }
                        }
                    }
    
                    // Handle documents - VERSION S3
                    if (isset($leconData['documents'])) {
                        $existingDocumentIds = $lecon->documents()->pluck('id')->toArray();
                        $updatedDocumentIds = [];
    
                        foreach ($leconData['documents'] as $documentData) {
                            if (isset($documentData['id'])) {
                                // Update existing document
                                $document = $lecon->documents()->findOrFail($documentData['id']);
                                $document->update([
                                    'titre' => $documentData['titre'] ?? 'Sans titre',
                                ]);
                                $updatedDocumentIds[] = $document->id;
    
                                // Update file if needed
                                if (isset($documentData['file'])) {
                                    // Delete old file (S3 ou local)
                                    if (str_starts_with($document->url, 'documents/')) {
                                        Storage::disk('s3')->delete($document->url);
                                    } else {
                                        Storage::delete(str_replace('/storage/', 'public/', $document->url));
                                    }
                                    
                                    // Upload new file to S3
                                    $documentName = time() . '_' . $documentData['file']->getClientOriginalName();
                                    $documentPath = $documentData['file']->storeAs('documents', $documentName, 's3');
                                    $document->url = $documentPath;
                                    $document->save();
                                }
                            } else if (isset($documentData['file'])) {
                                // Create new document on S3
                                $documentName = time() . '_' . $documentData['file']->getClientOriginalName();
                                $documentPath = $documentData['file']->storeAs('documents', $documentName, 's3');
                                
                                $document = $lecon->documents()->create([
                                    'titre' => $documentData['titre'] ?? 'Sans titre',
                                    'url' => $documentPath,
                                ]);
                                $updatedDocumentIds[] = $document->id;
                            }
                        }
    
                        // Delete documents that were not updated or created
                        foreach ($existingDocumentIds as $documentId) {
                            if (!in_array($documentId, $updatedDocumentIds)) {
                                $document = $lecon->documents()->find($documentId);
                                if ($document) {
                                    // Supprimer de S3 ou du stockage local
                                    if (str_starts_with($document->url, 'documents/')) {
                                        Storage::disk('s3')->delete($document->url);
                                    } else {
                                        Storage::delete(str_replace('/storage/', 'public/', $document->url));
                                    }
                                    $document->delete();
                                }
                            }
                        }
                    }
    
                    // Handle quiz
                    if (isset($leconData['quiz'])) {
                        $quizData = $leconData['quiz'];
                        
                        // Check if lesson already has a quiz
                        $quiz = $lecon->quiz;
                        
                        if ($quiz) {
                            // Update existing quiz
                            if (isset($quizData['id']) && $quizData['id'] == $quiz->id) {
                                $quiz->update([
                                    'titre' => $quizData['titre'],
                                    'noteFinale' => $quizData['noteFinale'],
                                    'dureeMaximale' => $quizData['dureeMaximale'],
                                ]);
                            } else {
                                // Delete old quiz and create new one
                                $quiz->delete();
                                $quiz = $lecon->quiz()->create([
                                    'titre' => $quizData['titre'],
                                    'noteFinale' => $quizData['noteFinale'],
                                    'dureeMaximale' => $quizData['dureeMaximale'],
                                ]);
                            }
                        } else {
                            // Create new quiz
                            $quiz = $lecon->quiz()->create([
                                'titre' => $quizData['titre'],
                                'noteFinale' => $quizData['noteFinale'],
                                'dureeMaximale' => $quizData['dureeMaximale'],
                            ]);
                        }
    
                        // Handle questions and answers
                        if (isset($quizData['questions'])) {
                            $existingQuestionIds = $quiz->questions()->pluck('id')->toArray();
                            $updatedQuestionIds = [];
    
                            foreach ($quizData['questions'] as $questionData) {
                                if (isset($questionData['id'])) {
                                    // Update existing question
                                    $question = $quiz->questions()->findOrFail($questionData['id']);
                                    $question->update([
                                        'contenu' => $questionData['contenu'],
                                    ]);
                                    $updatedQuestionIds[] = $question->id;
                                } else {
                                    // Create new question
                                    $question = $quiz->questions()->create([
                                        'contenu' => $questionData['contenu'],
                                    ]);
                                    $updatedQuestionIds[] = $question->id;
                                }
    
                                // Handle answers
                                if (isset($questionData['reponses'])) {
                                    $existingReponseIds = $question->reponses()->pluck('id')->toArray();
                                    $updatedReponseIds = [];
    
                                    foreach ($questionData['reponses'] as $reponseData) {
                                        if (isset($reponseData['id'])) {
                                            // Update existing answer
                                            $reponse = $question->reponses()->findOrFail($reponseData['id']);
                                            $reponse->update([
                                                'contenu' => $reponseData['contenu'],
                                                'est_correcte' => $reponseData['est_correcte'],
                                            ]);
                                            $updatedReponseIds[] = $reponse->id;
                                        } else {
                                            // Create new answer
                                            $reponse = $question->reponses()->create([
                                                'contenu' => $reponseData['contenu'],
                                                'est_correcte' => $reponseData['est_correcte'],
                                            ]);
                                            $updatedReponseIds[] = $reponse->id;
                                        }
                                    }
    
                                    // Delete answers that were not updated or created
                                    foreach ($existingReponseIds as $reponseId) {
                                        if (!in_array($reponseId, $updatedReponseIds)) {
                                            $question->reponses()->find($reponseId)->delete();
                                        }
                                    }
                                }
                            }
    
                            // Delete questions that were not updated or created
                            foreach ($existingQuestionIds as $questionId) {
                                if (!in_array($questionId, $updatedQuestionIds)) {
                                    $quiz->questions()->find($questionId)->delete();
                                }
                            }
                        }
                    } else if ($lecon->quiz) {
                        // Delete quiz if not included in update data
                        $lecon->quiz->delete();
                    }
                }
    
                // Delete lessons that were not updated or created - VERSION S3
                foreach ($existingLeconIds as $leconId) {
                    if (!in_array($leconId, $updatedLeconIds)) {
                        $lecon = $module->lecons()->find($leconId);
                        if ($lecon) {
                            // Delete associated videos
                            foreach ($lecon->videos as $video) {
                                if (str_starts_with($video->url, 'videos/')) {
                                    Storage::disk('s3')->delete($video->url);
                                } else {
                                    Storage::delete(str_replace('/storage/', 'public/', $video->url));
                                }
                            }
                            // Delete associated documents
                            foreach ($lecon->documents as $document) {
                                if (str_starts_with($document->url, 'documents/')) {
                                    Storage::disk('s3')->delete($document->url);
                                } else {
                                    Storage::delete(str_replace('/storage/', 'public/', $document->url));
                                }
                            }
                            $lecon->delete();
                        }
                    }
                }
            }
    
            // Delete modules that were not updated or created
            foreach ($existingModuleIds as $moduleId) {
                if (!in_array($moduleId, $updatedModuleIds)) {
                    $module = $formation->modules()->find($moduleId);
                    if ($module) {
                        // This will cascade delete lessons and related content
                        $module->delete();
                    }
                }
            }
    
            return response()->json([
                'message' => 'Formation mise à jour avec succès',
                'formation' => $formation->fresh()->load('modules.lecons.videos', 'modules.lecons.documents', 'modules.lecons.quiz.questions.reponses'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $formation = Formation::findOrFail($id);
        
        // Vérifier si l'utilisateur peut supprimer cette formation
        if (auth()->user()->role !== 'admin' && $formation->user_id !== auth()->id()) {
            return redirect()->route('formations.index')
                           ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette formation.');
        }
        
        // Supprimer les fichiers associés
        if ($formation->image_formation) {
            if (str_starts_with($formation->image_formation, 'formations/images')) {
                Storage::disk('s3')->delete($formation->image_formation);
            } else {
                Storage::delete('public/' . $formation->image_formation);
            }
        }
        
        foreach ($formation->modules as $module) {
            foreach ($module->lecons as $lecon) {
                foreach ($lecon->videos as $video) {
                    if (str_starts_with($video->url, 'videos/')) {
                        Storage::disk('s3')->delete($video->url);
                    } else {
                        Storage::delete('public/' . $video->file);
                    }
                }
                foreach ($lecon->documents as $document) {
                    if (str_starts_with($document->url, 'documents/')) {
                        Storage::disk('s3')->delete($document->url);
                    } else {
                        Storage::delete('public/' . $document->file);
                    }
                }
            }
        }
    
        // Supprimer la formation avec ses relations
        $formation->delete();
    
        return redirect()->route('formations.index')
                        ->with('success', 'Formation supprimée avec succès.');
    }
        /**
 * Get all formations the authenticated startup has paid for.
 *
 * @return \Illuminate\Http\Response
 */
public function paidFormations()
{
    // Get the authenticated startup
    $user = Auth::user();
    
    // Get all payments made by this startup that are completed
    $payments = Payment::where('user_id', $user->id)
                     ->where('status', 'completed')
                     ->with('formation') // Eager load the formation relationship
                     ->get();
    
    // Extract the formations from the payments
    $formations = $payments->map(function ($payment) {
        return $payment->formation;
    })->unique('id'); // Ensure no duplicate formations
    
    return response()->json(['formations' => $formations]);
}
/**
 * Get formations related to the specified formation.
 *
 * @param int $id The ID of the formation to find related formations for
 * @return \Illuminate\Http\Response
 */
public function relatedFormations($id)
{
    // Find the current formation
    $formation = Formation::findOrFail($id);
    
    // Get formations in the same category, limiting to 3 related formations
    $relatedFormations = Formation::select('id', 'titre', 'image_formation', 'prix', 'duree', 'description')
        ->where('category_id', $formation->category_id)
        ->where('id', '!=', $id) // Exclude the current formation
        ->where('est_valide', 'Validée')
        ->where('est_publiee', 1)
        ->with('user:id,first_name,last_name,email')
        ->withCount('modules')
        ->limit(3)
        ->latest()
        ->get();
    
    return response()->json([
        'formations' => $relatedFormations
    ]);
}
public function paidFormationsByStartupId()
{
    $user = Auth::user();
    
    // Determine which startup_id to use based on user role
    $startupId = null;
    
    if ($user->role === 'worker' && $user->startup_id) {
        // If the user is a worker, use their associated startup_id
        $startupId = $user->startup_id;
    }
    
    // If no valid startup ID was found, return an error
    if (!$startupId) {
        return response()->json(['message' => 'No valid startup association found.'], 403);
    }
    
    // Get all payments made by this startup that are completed
    $payments = Payment::where('user_id', $startupId)
                      ->where('status', 'completed')
                      ->with('formation') // Eager load the formation relationship
                      ->get();
    
    // Extract the formations from the payments
    $formations = $payments->map(function ($payment) {
        return $payment->formation;
    })->unique('id')->values(); // Ensure no duplicate formations and reset keys
    
    return response()->json(['formations' => $formations]);
}

    /**
     * Acheter une formation
     */
    public function acheter(Request $request, $id)
    {
        // S'assurer que l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')
                           ->with('error', 'Vous devez être connecté pour acheter une formation.');
        }

        $formation = Formation::findOrFail($id);
        $user = auth()->user();

        // Vérifier si l'utilisateur possède déjà cette formation
        $existingPurchase = FormationUser::where('user_id', $user->id)
                                        ->where('formation_id', $formation->id)
                                        ->first();

        if ($existingPurchase) {
            // Si la formation est expirée, on peut la réactiver
            if (Carbon::now()->greaterThan($existingPurchase->date_expiration)) {
                $dateAchat = Carbon::now();
                $dateExpiration = $this->calculerDateExpiration($dateAchat, $formation->duree);
                
                $existingPurchase->update([
                    'date_achat' => $dateAchat,
                    'date_expiration' => $dateExpiration,
                ]);

                return redirect()->route('formations.show', $formation->id)
                               ->with('success', 'Votre accès à cette formation a été renouvelé jusqu\'au ' . 
                               $dateExpiration->format('d/m/Y H:i'));
            }
            
            return redirect()->route('formations.show', $formation->id)
                           ->with('info', 'Vous possédez déjà cette formation. Expire le ' . 
                           $existingPurchase->date_expiration->format('d/m/Y H:i'));
        }

        // Traitement de l'achat (ici on pourrait ajouter une logique de paiement)
        try {
            $dateAchat = Carbon::now();
            $dateExpiration = $this->calculerDateExpiration($dateAchat, $formation->duree);

            FormationUser::create([
                'user_id' => $user->id,
                'formation_id' => $formation->id,
                'date_achat' => $dateAchat,
                'date_expiration' => $dateExpiration,
            ]);

            return redirect()->route('formations.show', $formation->id)
                           ->with('success', 'Formation achetée avec succès! Accès disponible jusqu\'au ' . 
                           $dateExpiration->format('d/m/Y H:i'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'achat: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les formations achetées par l'utilisateur
     */
    public function mesFormations()
    {
        // S'assurer que l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')
                           ->with('error', 'Vous devez être connecté pour voir vos formations.');
        }

        $formationsUser = FormationUser::where('user_id', auth()->id())
                                      ->with('formation')
                                      ->get()
                                      ->map(function ($item) {
                                          return [
                                              'id' => $item->id,
                                              'formation' => $item->formation,
                                              'date_achat' => $item->date_achat->format('d/m/Y'),
                                              'date_expiration' => $item->date_expiration->format('d/m/Y'),
                                              'temps_restant' => $item->getTempsRestant(),
                                              'compteur' => $item->getCompteurTemps(),
                                              'est_expire' => Carbon::now()->greaterThan($item->date_expiration)
                                          ];
                                      });

        return Inertia::render('Formations/MesFormations', [
            'formationsUser' => $formationsUser
        ]);
    }

    /**
     * Calcule la date d'expiration en fonction de la durée
     */
    private function calculerDateExpiration($dateAchat, $duree)
    {
        // Convertir la date d'achat en Carbon si ce n'est pas déjà fait
        $dateAchat = $dateAchat instanceof Carbon ? $dateAchat : Carbon::parse($dateAchat);
        
        // Analyser la durée (supposons qu'elle soit au format "X mois" ou "X jours")
        if (preg_match('/(\d+)\s*mois/i', $duree, $matches)) {
            return $dateAchat->copy()->addMonths((int)$matches[1]);
        } elseif (preg_match('/(\d+)\s*jour/i', $duree, $matches)) {
            return $dateAchat->copy()->addDays((int)$matches[1]);
        } elseif (preg_match('/(\d+)\s*semaine/i', $duree, $matches)) {
            return $dateAchat->copy()->addWeeks((int)$matches[1]);
        } elseif (preg_match('/(\d+)\s*an/i', $duree, $matches)) {
            return $dateAchat->copy()->addYears((int)$matches[1]);
        }
        
        // Par défaut, si le format n'est pas reconnu, ajouter 30 jours
        return $dateAchat->copy()->addDays(30);
    }
}