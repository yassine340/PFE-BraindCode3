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

Route::get('/DashboardAdmin', function () {
    return Inertia::render('DashboardAdmin');
})->middleware(['auth', 'verified'])->name('DashboardAdmin');
// Route pour afficher le tableau de bord formateur
Route::get('/DashboardFormateur', function () {
    return Inertia::render('DashboardFormateur');
})->name('DashboardFormateur');

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
// Afficher le formulaire de création d'une formation

// Afficher toutes les formations (index)
Route::get('/formations/index', [FormationController::class, 'index'])->name('formations.index');

Route::middleware(['auth','verified',AdminFormateurMiddleware::class])->group(function () {
    Route::get('/formations/create', [FormationController::class, 'create'])->name('formations.create');
    // Enregistrer la formation dans la base de données
    Route::post('/formations', [FormationController::class, 'store'])->name('formations.store');
    Route::get('/formations/count', [AdminController::class, 'countFormations']);
    Route::get('/formations/{id}', [FormationController::class, 'show'])->name('formations.show');
    // Supprimer une formation (DELETE)
    Route::delete('/formations/{id}', [FormationController::class, 'destroy'])->name('formations.destroy');
    // Afficher le formulaire de modification d'une formation
    Route::get('/formations/{id}/edit', [FormationController::class, 'edit'])->name('formations.edit');
    // Mettre à jour une formation (PUT)
    Route::put('/formations/{id}', [FormationController::class, 'update'])->name('formations.update');

        // Afficher une formation (GET)
    Route::get('/formations/count', [AdminController::class, 'countFormations'])
    ->Middleware(['verified']); 
    // Supprimer une formation (DELETE)
    Route::delete('/formations/{id}', [FormationController::class, 'destroy'])->name('formations.destroy');
    // Afficher le formulaire de modification d'une formation
    Route::get('/formations/{id}/edit', [FormationController::class, 'edit'])->name('formations.edit');
    // Mettre à jour une formation (PUT)
    Route::put('/formations/{id}', [FormationController::class, 'update'])->name('formations.update');
});

Route::get('/formations/{id}', [FormationController::class, 'show'])->name('formations.show');

// Afficher le formulaire avec catégories
Route::get('/formationscat', function (Request $request) {
    $query = Formation::query();

    if ($request->has('category') && $request->category !== "") { 
        $query->where('category_id', $request->category);
    }

    // Fetch categories for the form
    $categories = Category::all();

    // Get filtered formations
    $formations = $query->get();

    return inertia('Formations/Index', [
        'formations' => $formations,
        'categories' => $categories, // Pass categories to the view
    ]);
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

Route::get('/formations', function (Request $request) {
    try {
        $query = $request->input('search'); // Get the search query from the request

        $formations = Formation::query();

        // If there's a search query, apply filtering
        if ($query) {
            // Search in the titre field
            $formations->where('titre', 'LIKE', "%$query%");

            // If query is numeric, search in prix
            if (is_numeric($query)) {
                $formations->orWhere('prix', $query);
            }

            // Search in modules and their lecons (lesson titles)
            $formations->orWhereHas('modules', function ($q) use ($query) {
                $q->where('titre', 'LIKE', "%$query%")
                  ->orWhereHas('lecons', function ($q2) use ($query) {
                      $q2->where('titre', 'LIKE', "%$query%");
                  });
            });
        }

        // Return filtered formations or all formations if no search query is provided
        return inertia('Formations/Index', [
            'formations' => $formations->get(), // Return the filtered or all formations
        ]);
    } catch (\Exception $e) {
        Log::error('Search error: ' . $e->getMessage());
        
        // Optionally, return all formations if there is an error
        return inertia('Formations/Index', [
            'formations' => Formation::all(), // Return all formations on error
            'error' => 'Server error: ' . $e->getMessage(), // Pass the error message
        ]);
    }
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

// Route pour afficher les statistiques de l'utilisateur
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