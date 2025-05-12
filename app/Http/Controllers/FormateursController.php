<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Formation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormateursController extends Controller
{
    public function show($id)
    {
        $formateur = User::where('role', 'formateur')
                     ->with(['formations' => function($query) {
                         $query->select('id', 'titre', 'image_formation', 'est_valide', 'est_publiee', 'user_id', 'prix', 'estcertifiante', 'category_id', 'created_at', 'description', 'duree')
                               ->withCount('modules');
                     }])
                     ->findOrFail($id);
        
        return Inertia::render('Formateurs/Show', [
            'formateur' => $formateur,
            'formations' => $formateur->formations
        ]);
    }

    /**
     * Get all formations created by a specific formateur
     */
    public function getFormations($id)
    {
        $formateur = User::where('role', 'formateur')->findOrFail($id);
        
        $formations = Formation::where('user_id', $formateur->id)
                      ->select('id', 'titre', 'image_formation', 'est_valide', 'est_publiee', 'prix', 'estcertifiante', 'category_id', 'created_at', 'description', 'duree')
                      ->withCount('modules')
                      ->get();
        
        // Process images to ensure they exist
        $formations->transform(function ($formation) {
            if ($formation->image_formation) {
                // Check if the image exists and is accessible
                $path = public_path($formation->image_formation);
                if (!file_exists($path)) {
                    // If image doesn't exist, set to null
                    $formation->image_formation = null;
                }
            }
            return $formation;
        });
        
        return Inertia::render('Formateurs/Formations', [
            'formateur' => [
                'id' => $formateur->id,
                'first_name' => $formateur->first_name,
                'last_name' => $formateur->last_name,
                'email' => $formateur->email
            ],
            'formations' => $formations
        ]);
    }

    /**
     * Get all formations created by the authenticated formateur
     */
    public function myFormations()
    { 
        $formations = Formation::where('user_id', auth()->id())
                      ->select('id', 'titre', 'image_formation', 'est_valide', 'est_publiee', 'prix', 'estcertifiante', 'category_id', 'created_at', 'description', 'duree')
                      ->withCount('modules')
                      ->where('est_valide','Validée') 
                      ->get();
        
        return Inertia::render('Formateurs/MyFormations', [
            'formations' => $formations
        ]);
    }

    /**
     * Display formations by status (published, unpublished, validated, etc.)
     */
    public function formationsByStatus(Request $request, $id)
    {
        $formateur = User::where('role', 'formateur')->findOrFail($id);
        
        $query = Formation::where('user_id', $formateur->id)
                ->select('id', 'titre', 'image_formation', 'est_valide', 'est_publiee', 'prix', 'estcertifiante', 'category_id', 'created_at', 'description', 'duree')
                ->withCount('modules');
                
        // Filter by publication status
        if ($request->has('est_publiee')) {
            $query->where('est_publiee', $request->est_publiee);
        }
        
        // Filter by validation status
        if ($request->has('est_valide')) {
            $query->where('est_valide', $request->est_valide);
        }
        
        $formations = $query->get();
        
        // Process images to ensure they exist
        $formations->transform(function ($formation) {
            if ($formation->image_formation) {
                // Check if the image exists and is accessible
                $path = public_path($formation->image_formation);
                if (!file_exists($path)) {
                    // If image doesn't exist, set to null
                    $formation->image_formation = null;
                }
            }
            return $formation;
        });
        
        return Inertia::render('Formateurs/FormationsByStatus', [
            'formateur' => [
                'id' => $formateur->id,
                'first_name' => $formateur->first_name,
                'last_name' => $formateur->last_name,
                'email' => $formateur->email
            ],
            'formations' => $formations,
            'filters' => [
                'est_publiee' => $request->est_publiee,
                'est_valide' => $request->est_valide
            ]
        ]);
    }
    
    /**
     * Get statistics about formateur's formations
     */
    public function statistics($id)
    {
        $formateur = User::where('role', 'formateur')->findOrFail($id);
        
        // Count formations by status
        $totalFormations = Formation::where('user_id', $formateur->id)->count();
        $publishedFormations = Formation::where('user_id', $formateur->id)->where('est_publiee', 1)->count();
        $validatedFormations = Formation::where('user_id', $formateur->id)->where('est_valide', 'Validée')->count();
        
        // Get latest formations
        $latestFormations = Formation::where('user_id', $formateur->id)
                           ->select('id', 'titre', 'created_at')
                           ->latest()
                           ->take(5)
                           ->get();
        
        return Inertia::render('Formateurs/Statistics', [
            'formateur' => [
                'id' => $formateur->id,
                'first_name' => $formateur->first_name,
                'last_name' => $formateur->last_name,
                'email' => $formateur->email
            ],
            'stats' => [
                'totalFormations' => $totalFormations,
                'publishedFormations' => $publishedFormations,
                'validatedFormations' => $validatedFormations,
                'latestFormations' => $latestFormations
            ]
        ]);
    }
}