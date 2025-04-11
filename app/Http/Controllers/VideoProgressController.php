<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VideoProgressController extends Controller
{
    /**
     * Enregistrer ou mettre à jour la progression d'une vidéo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Requête de progression vidéo reçue', [
            'data' => $request->all(),
            'user_id' => Auth::id(),
            'ip' => $request->ip()
        ]);
        
        try {
            $validated = $request->validate([
                'video_id' => 'required|exists:videos,id',
                'current_time' => 'required|integer|min:0',
                'completed' => 'boolean'
            ]);
            
            Log::info('Données validées', $validated);
            
            if (!Auth::check()) {
                Log::error('Utilisateur non authentifié');
                return response()->json([
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }
            
            Log::info('Tentative de création/mise à jour', [
                'user_id' => Auth::id(),
                'video_id' => $validated['video_id']
            ]);
            
            // Créer ou mettre à jour la progression
            $videoProgress = VideoProgress::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'video_id' => $validated['video_id']
                ],
                [
                    'current_time' => $validated['current_time'],
                    'completed' => $validated['completed'] ?? false
                ]
            );
            
            Log::info('Progression sauvegardée', [
                'id' => $videoProgress->id,
                'user_id' => $videoProgress->user_id,
                'video_id' => $videoProgress->video_id,
                'current_time' => $videoProgress->current_time
            ]);
            
            return response()->json([
                'message' => 'Progression sauvegardée avec succès',
                'data' => $videoProgress
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la sauvegarde de la progression', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtenir la progression d'une vidéo pour l'utilisateur actuel.
     *
     * @param  int  $videoId
     * @return \Illuminate\Http\Response
     */
    public function show($videoId)
    {
        Log::info('Requête de lecture de progression', [
            'user_id' => Auth::id(),
            'video_id' => $videoId
        ]);
        
        if (!Auth::check()) {
            Log::error('Utilisateur non authentifié pour lecture');
            return response()->json([
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }
        
        $progress = VideoProgress::where('user_id', Auth::id())
            ->where('video_id', $videoId)
            ->first();
            
        if (!$progress) {
            Log::info('Aucune progression trouvée', [
                'user_id' => Auth::id(),
                'video_id' => $videoId
            ]);
            
            return response()->json([
                'current_time' => 0,
                'completed' => false
            ]);
        }
        
        Log::info('Progression trouvée', [
            'id' => $progress->id,
            'current_time' => $progress->current_time,
            'completed' => $progress->completed
        ]);
        
        return response()->json([
            'current_time' => $progress->current_time,
            'completed' => $progress->completed
        ]);
    }
}