<?php

use App\Http\Controllers\ProfileController;
use Doctrine\DBAL\Logging\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\DocumentController;
use App\Models\Category;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AdminFormateurMiddleware;
use App\Http\Controllers\FormateursController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StartupController;
use App\Http\Middleware\AdminStartupMiddleware;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AIFormationController;

// Route pour afficher la page d'accueil
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//************************************************************************************************* */

// Route pour afficher le tableau de bord utilisateur
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//************************************************************************************************* */

// Route pour afficher le tableau de bord formateur
Route::middleware(['auth','verified',AdminFormateurMiddleware::class])->group(function () {
    Route::get('/DashboardFormateur', function () {
        return Inertia::render('DashboardFormateur'); 
    })->name('DashboardFormateur'); 
    Route::get('api/coach/{id}/total-buyers', [PaymentController::class, 'getTotalBuyersForCoach']);
    Route::get('api/coach/{id}/total-money', [PaymentController::class, 'getTotalMoneyForCoach']);
}); 

//************************************************************************************************* */

// Authentification
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//************************************************************************************************* */

// Route pour afficher le tableau de bord administrateur
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboardAdmin', function () {
        return Inertia::render('DashboardAdmin');
    })->name('dashboardAdmin');
    
    Route::get('/payments', [AdminController::class, 'index']);
    Route::get('/payments/statistics', [AdminController::class, 'statistics']);
    Route::get('/payments/{id}', [AdminController::class, 'show']);
    //Router API 
    Route::get('/api/formateur-en-attente', [AdminController::class, 'countFormateurEnAttente']);

    // Route pour afficher la liste des formateurs en attente
    Route::get('/formateurs-en-attente', [AdminController::class, 'getFormateursEnAttente'])
        ->name('formateur.en.attente');
    // Valider un formateur
    Route::post('/formateurs/{id}/valider', [AdminController::class, 'validerFormateur'])->name('formateurs.valider');
    // Rejeter un formateur
    Route::post('/formateurs/{id}/rejeter', [AdminController::class, 'rejeterFormateur'])->name('formateurs.rejeter');
    //Afficher les formateurs en attente
    Route::get('/formateurs', [AdminController::class, 'Listeformateur'])->name('formateurs.index');
    //Afficher une formateurs
    Route::get('/formateurs/{id}', [AdminController::class, 'showFormateur'])->name('formateurs.show');
    //Afficher le formulaire de modification d'un formateur
    Route::get('/formateurs/{id}/edit', [AdminController::class, 'editFormateur'])->name('formateurs.edit');
    //Mettre à jour un formateur
    Route::put('/formateurs/{id}', [AdminController::class, 'updateFormateur'])->name('formateurs.update');
    //Supprimer un formateur
    Route::delete('/formateurs/{id}', [AdminController::class, 'deleteFormateur'])->name('formateur.delete');
    
    Route::get('coach/earnings',function () {
        return Inertia::render('CoachEarnings');
    })->name('coach.earnings');

    Route::get('/api/coach/earnings', [PaymentController::class, 'getCoachEarnings']);
});

require __DIR__.'/auth.php';

//************************************************************************************************* */

// Route pour stocker une vidéo
Route::post('/upload-video', [VideoController::class, 'upload']);
//video
Route::get('/videos', [VideoController::class, 'getVideos']);
Route::get('/upload-videos', function () {
    return Inertia::render('upload_videos');
})->name('upload.videos');
Route::get('/afficher-videos', function () {
    return Inertia::render('afficher_videos');
})->name('afficher.videos');
// Route pour stocker un document
Route::post('/document/store', [DocumentController::class, 'store'])->name('document.store');

//********************************************************************************************** */
Route::get('/document-upload', function () {
    return Inertia::render('UploadDocument');
})->name('document-upload');
// Route pour afficher la liste des documents, avec les données envoyées par le contrôleur
Route::get('/document-list', [DocumentController::class, 'index'])->name('document-list');

// Route pour récupérer les documents via le contrôleur
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');

//************************************************************************************************* */

// Afficher toutes les formations (index)
Route::get('/formations', [FormationController::class, 'index'])->name('formations.index');

//************************************************************************************************* */

// API route that returns JSON data
Route::get('/api/formations-en-attente', [AdminController::class, 'getFormationsEnAttente2']);
// Route pour afficher la liste des formations en attente
Route::get('/formations-en-attente', [AdminController::class, 'getFormationsEnAttente'])
    ->name('formation.en.attente');
// Valider une formation
Route::post('/formations/{id}/valider', [AdminController::class, 'validerFormation'])
    ->name('formations.valider');
// Rejeter une formation
Route::post('/formations/{id}/rejeter', [AdminController::class, 'rejeterFormation'])
    ->name('formations.rejeter');

Route::middleware(['auth','verified'])->group(function () {
   // Afficher toutes les formations (index)
    Route::get('/formations/index', [FormationController::class, 'index'])->name('formations.index');
});

Route::middleware(['auth','verified',AdminFormateurMiddleware::class])->group(function () {
    Route::get('/formations/create', [FormationController::class, 'create'])->name('formations.create');
    // Enregistrer la formation dans la base de données
    Route::post('/formations', [FormationController::class, 'store'])->name('formations.store');
    Route::get('/formations/count', [AdminController::class, 'countFormations']);
    // Supprimer une formation (DELETE)
    Route::delete('/formations/{id}', [FormationController::class, 'destroy'])->name('formations.destroy');
    // Afficher le formulaire de modification d'une formation
    Route::get('/formations/{id}/edit', [FormationController::class, 'edit'])->name('formations.edit');
    // Mettre à jour une formation (PUT)
    Route::put('/formations/{id}', [FormationController::class, 'update'])->name('formations.update');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/formations/{id}', [FormationController::class, 'show'])->name('formations.show');
    Route::get('/formationscat', function (Request $request) {
        $query = Formation::query()
            ->where('est_valide', 'Validée')
            ->where('est_publiee', 1);

        if ($request->has('category') && $request->category !== "") { 
            $query->where('category_id', $request->category);
        }

        // Fetch categories for the form
        $categories = Category::all();

        // Get filtered formations
        $formations = $query->get();

        return inertia('Formations/Index', [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    });
});

Route::middleware(['auth','verified',AdminMiddleware::class])->group(function () {
    // Route pour afficher la liste des catégories
    Route::get('/categorie', [CategoryController::class, 'index'])->name('categories.index');
    // Route pour afficher le formulaire de création d'une catégorie
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    // Route pour enregistrer une catégorie
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    // Route pour supprimer une catégorie
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    // Route pour mettre à jour une catégorie
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    // Route pour afficher le formulaire de modification d'une catégorie
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
});

// afficher les categories
Route::get('/categories', function () {
    return response()->json(Category::all());
});

//************************************************************************************************* */
// route pour afficher les formations cherchées
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/formations', function (Request $request) {
        try {
            $search = $request->input('search');

            // Base query: only validated and published formations
            $formations = Formation::query()
                ->where('est_valide', 'Validée')
                ->where('est_publiee', 1);

            if ($search) {
                $formations->where(function ($query) use ($search) {
                    $query->where('titre', 'LIKE', "%$search%");

                    if (is_numeric($search)) {
                        $query->orWhere('prix', $search);
                    }

                    $query->orWhereHas('modules', function ($q) use ($search) {
                        $q->where('titre', 'LIKE', "%$search%")
                          ->orWhereHas('lecons', function ($q2) use ($search) {
                              $q2->where('titre', 'LIKE', "%$search%");
                          });
                    });
                });
            }

            return inertia('Formations/Index', [
                'formations' => $formations->get(),
            ]);
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());

            return inertia('Formations/Index', [
                'formations' => Formation::where('est_valide', 'Validée')
                                         ->where('est_publiee', 1)
                                         ->get(),
                'error' => 'Server error: ' . $e->getMessage(),
            ]);
        }
    });
});

// Creer un quizz
//************************************************************** */
Route::post('/quizzes/store', [QuizController::class, 'store']);
Route::get('/quizzes/lecon/{lecon_id}', [QuizController::class, 'getByLecon']);

/****************************************************************** */

// Creer une Question
//************************************************************** */
Route::post('/questions', [QuestionController::class, 'store']);
/****************************************************************** */
// Creer des Reponses
//************************************************************** */
Route::post('/reponses', [ReponseController::class, 'storeMultiple']);
//Route::post('/reponses', [ReponseController::class, 'showResponses']);
/****************************************************************** */
// Afficher le formulaire de création d'un quizz
Route::get('/quizManager', function () {
    return Inertia::render('QuizManager');
})->name('quizManager');
//************************************************************************************************* */
//Partie Gamification
Route::post('/gamification/submit', [GamificationController::class, 'store'])->name('gamification.store');
// Ajouter cette route avec vos autres routes
Route::get('/gamification/stats/{userId?}', [GamificationController::class, 'getUserStats'])
    ->name('gamification.stats');

// Route pour la vue (page des statistiques)
Route::get('/stats', function () {
    return Inertia::render('Stats');
})->middleware(['auth'])->name('user.stats');

// Routes de paiement
Route::post('/stripe/create-intent', [PaymentController::class, 'createStripeIntent']);
Route::post('/stripe/confirm-payment', [PaymentController::class, 'confirmStripePayment']);
Route::get('/check-payment-status/{userId}/{formationId}', [PaymentController::class, 'checkPaymentStatus']);
Route::post('/stripe/record-failed-payment', [PaymentController::class, 'recordFailedStripePayment']);

// Add PayPal routes
Route::post('/paypal/create-order', [PaymentController::class, 'createPayPalOrder']);
Route::post('/paypal/capture-order', [PaymentController::class, 'capturePayPalOrder']);
Route::post('/paypal/record-failed-payment', [PaymentController::class, 'recordFailedPayPalPayment']);

//admin transaction
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/PaymentTransactionsAdmin',function () {
        return Inertia::render('Admin/PaymentTransactionsAdmin');
    })->name('PaymentTransactionsAdmin');

    // Route pour afficher tous les utilisateurs (admin)
    Route::get('/admin/users', [AdminController::class, 'allUsers'])
    ->name('admin.users')
    ->middleware(['verified' ]);
    // Nouvelles routes à ajouter
    Route::get('/admin/users/{user}', [AdminController::class, 'showUser'])
    ->name('admin.users.show')
    ->middleware(['verified']);
    
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])
    ->name('admin.users.edit')
    ->middleware(['verified']);
    
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])
    ->name('admin.users.delete')
    ->middleware(['verified']);
    Route::get('/users/count', [AdminController::class, 'countUser'])
    ->Middleware(['verified']);
});

// Add this to your routes/web.php file
Route::get('/formation/{id}', function ($id, Request $request) {
    Log::info('Return from PayPal detected', [
        'formation_id' => $id,
        'query_params' => $request->all()
    ]);
    
    // Continue to your normal controller action
    return app(FormationController::class)->show($id);
})->name('formation.show');

Route::middleware(['auth'])->group(function () {
    // View a formateur's profile
    Route::get('/formateurs/{id}', [FormateursController::class, 'show'])->name('formateurs.show2');
    
    // Get all formations by a formateur
    Route::get('/formateurs/{id}/formations', [FormateursController::class, 'getFormations'])->name('formateurs.formations');
    
    // Formateur's formations filtered by status
    Route::get('/formateurs/{id}/formations/status', [FormateursController::class, 'formationsByStatus'])->name('formateurs.formations.status');
    
    // Formateur's statistics
    Route::get('/formateurs/{id}/statistics', [FormateursController::class, 'statistics'])->name('formateurs.statistics');
    
    // Authenticated formateur's own formations
    Route::get('/my-formations', [FormateursController::class, 'myFormations'])
        ->middleware(AdminFormateurMiddleware::class)
        ->name('formateurs.my-formations');
});

// Pages statiques
// Route pour afficher FAQPage
Route::get('/faq', function () {
    return Inertia::render('FAQPage');
})->name('faq');

// Route pour afficher la page de contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Process form submission
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Route pour afficher la page de politique de confidentialité
Route::get('/politique-de-confidentialite', function () {
    return Inertia::render('PolitiqueConfidentialite');
})->name('politique-de-confidentialite');

// Route pour afficher la page de Startup Investment
Route::get('/startup-investment', function () {
    return Inertia::render('StartupInvestment');
})->name('startup-investment');

// Route pour afficher la page de coach
Route::get('/coach', function () {
    return Inertia::render('coach');
})->name('coach');

// Route pour afficher la page de Startup
Route::get('/startup', function () {
    return Inertia::render('Startup');
})->name('startup');

// Route pour afficher la page de Forum
Route::get('/forum', function () {
    return Inertia::render('Forum');
})->name('forum');

// Routes S3
Route::get('/test-s3', function () {
    try {
        // Test simple: créer un fichier texte sur S3
        $result = Storage::disk('s3')->put('test.txt', 'Bonjour depuis Laravel!');
        
        if ($result) {
            return "Succès! Fichier créé sur S3";
        } else {
            return "Échec: Impossible de créer le fichier";
        }
    } catch (\Exception $e) {
        return "Erreur: " . $e->getMessage();
    }
});

// Route pour lister les fichiers S3
Route::get('/api/s3-files', [PaymentController::class, 'listS3Files'])->name('api.s3-files');

Route::get('/s3-files-manager', function () {
    return Inertia::render('S3FilesManager');
})->middleware(['auth'])->name('s3-files-manager');

Route::middleware(['auth', AdminStartupMiddleware::class])->group(function () {
    Route::get('/Dashboardstartup', function () {
        return Inertia::render('Dashboardstartup');
    })->name('Dashboardstartup');
    
    // API endpoints for workers
    Route::get('/workers', [StartupController::class, 'listWorkers']);
    Route::post('/workers', [StartupController::class, 'addWorker']);
    Route::put('/workers/{id}', [StartupController::class, 'updateWorker']);
    Route::delete('/workers/{id}', [StartupController::class, 'deleteWorker']);
    Route::get('/startup/formations/paid', [FormationController::class, 'paidFormationsForWorker']);
    
    // API endpoints for groups - now using StartupController
    Route::get('/api/groups', [StartupController::class, 'listGroups']);
    Route::post('/groups', [StartupController::class, 'createGroup']);
    Route::get('/groups/{id}', [StartupController::class, 'showGroup']);
    Route::put('/groups/{id}', [StartupController::class, 'updateGroup']);
    Route::delete('/groups/{id}', [StartupController::class, 'deleteGroup']);
    
    // API endpoints for adding/removing workers to/from groups - now using StartupController
    Route::post('/groups/add-worker', [StartupController::class, 'addWorkerToGroup']);
    Route::post('/groups/remove-worker', [StartupController::class, 'removeWorkerFromGroup']);
    Route::get('/group-workers', [StartupController::class, 'getWorkersForGroup']);
});

// Routes formations payées
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/mes-formations-payees', function () {
        return Inertia::render('Mes_Formations_Payé');
    })->name('mes-formations-payees');
    Route::get('/api/formations/paid', [FormationController::class, 'paidFormations']);
    // Route pour afficher la page de relatedFormations
    Route::get('/related-formations/{id}',[FormationController::class, 'relatedFormations'])->name('related-formations');
});

Route::middleware(['auth','verified'])->group(function () {
    // Route pour afficher la page de mes formations
    Route::get('/api/startup/formations/paid', [FormationController::class, 'paidFormationsByStartupId']);
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/worker/formations/paid', function () {
        return Inertia::render('Mes_Formations_Payé_startup');
    })->name('mes-formations-payees-startup');
});

Route::get('/test-404', function () {
    abort(404);
});

Route::get('/groups', function () {
    return Inertia::render('Groups');
})->name('groups.index');

Route::get('/TeamPage', function () {
    return Inertia::render('TeamPage');
})->name('team.index');
Route::middleware(['auth'])->group(function () {
    Route::get('/formateur/ai-formation', [AIFormationController::class, 'index'])->name('formateur.ai-formation');
    Route::post('/formateur/generate-formation', [AIFormationController::class, 'generate'])->name('formateur.generate-formation');
Route::match(['get', 'post'], '/formateur/generate-formation-stream', [AIFormationController::class, 'generateStream'])->name('formateur.generate-formation-stream');
});