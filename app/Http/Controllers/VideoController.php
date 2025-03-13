<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function upload(Request $request)
    {
        // Validation du fichier vidéo
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:102400', // Max 100MB
        ]);
    
        // Récupérer le vrai nom du fichier
        $originalName = $request->file('video')->getClientOriginalName();
    
        // Nettoyer le nom du fichier pour éviter les erreurs (espaces, caractères spéciaux)
        $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName); 
    
        // Vérifier si un fichier avec le même nom existe déjà, sinon l'écraser
        if (Storage::disk('public')->exists("videos/{$fileName}")) {
            return response()->json([
                'message' => 'Une vidéo avec ce nom existe déjà !',
                'url' => null
            ], 400);
        }
    
        // Stocker la vidéo dans storage/app/public/videos/
        $path = $request->file('video')->storeAs('videos', $fileName, 'public');
    
        return response()->json([
            'message' => 'Vidéo uploadée avec succès !',
            'url' => Storage::url($path)
        ]);
    }
    
    
    public function getVideos()
    {
        $files = Storage::disk('public')->files('videos'); // Préciser "disk('public')"
        
        $videos = array_map(function ($file) {
            return asset("storage/" . $file); // Générer une URL publique correcte
        }, $files);
    
        return response()->json($videos);
    }
    
    
    
}
