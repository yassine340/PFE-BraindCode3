<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VideoProgressController extends Controller
{
    public function saveProgress(Request $request)
    {
        $validated = $request->validate([
            'video_id' => 'required|string',
            'current_time' => 'required|numeric|min:0',
            'duration' => 'nullable|numeric|min:0'
        ]);
        
        $validated['user_id'] = Auth::id();
        
        VideoProgress::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'video_id' => $validated['video_id']
            ],
            [
                'current_time' => $validated['current_time'],
                'duration' => $validated['duration'] ?? null
            ]
        );
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Récupérer la progression d'une vidéo
     */
    public function getProgress(Request $request)
    {
        $validated = $request->validate([
            'video_id' => 'required|string'
        ]);
        
        $progress = VideoProgress::where('user_id', Auth::id())
            ->where('video_id', $validated['video_id'])
            ->first();
        
        if (!$progress) {
            return response()->json([
                'current_time' => 0,
                'duration' => null
            ]);
        }
        
        return response()->json([
            'current_time' => $progress->current_time,
            'duration' => $progress->duration
        ]);
    }
    
    /**
     * Réinitialiser la progression d'une vidéo
     */
    public function resetProgress(Request $request)
    {
        $validated = $request->validate([
            'video_id' => 'required|string'
        ]);
        
        VideoProgress::where('user_id', Auth::id())
            ->where('video_id', $validated['video_id'])
            ->delete();
        
        return response()->json(['success' => true]);
    }

}