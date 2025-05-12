<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AIFormationController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function index()
    {
        // Afficher le formulaire de génération de formation
        return Inertia::render('Formateur/AIFormation');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'details' => 'nullable|string',
            'level' => 'nullable|string|in:débutant,intermédiaire,avancé',
        ]);

        try {
            $content = $this->openAIService->generateFormationContent(
                $request->topic,
                $request->details ?? '',
                $request->level ?? 'intermédiaire'
            );

            // Tenter de décoder le JSON fourni par l'IA
            $formationData = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Si le format JSON n'est pas valide, on renvoie le texte brut
                return response()->json([
                    'success' => true,
                    'data' => $content,
                    'is_json' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $formationData,
                'is_json' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur de génération IA: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateStream(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'details' => 'nullable|string',
            'level' => 'nullable|string|in:débutant,intermédiaire,avancé',
        ]);

        return response()->stream(function () use ($request) {
            try {
                // Envoyer un message de début
                echo "data: " . json_encode(['type' => 'start']) . "\n\n";
                ob_flush();
                flush();

                // Obtenir le stream directement avec les bons paramètres
                $stream = $this->openAIService->streamFormationContent(
                    $request->topic,
                    $request->details ?? '',
                    $request->level ?? 'intermédiaire'
                );

                $fullText = '';
                
                // Parcourir le stream et envoyer chaque chunk
                foreach ($stream as $chunk) {
                    $fullText .= $chunk;
                    
                    echo "data: " . json_encode([
                        'type' => 'update',
                        'content' => $chunk
                    ]) . "\n\n";
                    
                    ob_flush();
                    flush();
                }
                
                // À la fin, essayer de parser en JSON
                $isJson = false;
                $jsonData = null;
                
                try {
                    $jsonData = json_decode($fullText, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $isJson = true;
                    }
                } catch (\Exception $e) {
                    // Format non-JSON, on continue avec le texte brut
                }
                
                // Envoyer le message final avec les données complètes
                echo "data: " . json_encode([
                    'type' => 'complete',
                    'is_json' => $isJson,
                    'data' => $isJson ? $jsonData : $fullText
                ]) . "\n\n";
                
            } catch (\Exception $e) {
                Log::error('Erreur de streaming IA: ' . $e->getMessage());
                
                echo "data: " . json_encode([
                    'type' => 'error',
                    'message' => 'Erreur lors de la génération: ' . $e->getMessage()
                ]) . "\n\n";
            }
            
            ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no'
        ]);
    }
}