<?php

namespace App\Services;

use OpenAI;
use Exception;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $apiKey = config('openai.Openai.key');
        if (empty($apiKey)) {
            // Essayez de récupérer directement de l'environnement
            $apiKey = env('OPENAI_KEY');
            
            if (empty($apiKey)) {
                throw new Exception("La clé API OpenAI n'est pas configurée correctement");
            }
        }
        $this->client = OpenAI::client($apiKey);
    }

    public function generateFormationContent($topic, $details = '', $level = 'intermédiaire')
    {
        try {
            $prompt = $this->buildPrompt($topic, $details, $level);
            
            $response = $this->client->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'Vous êtes un expert en création de formations éducatives. Vous devez créer des contenus structurés, pédagogiques et détaillés.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 4000,
                // Retirez 'stream' => true de cette méthode
            ]);

            return $response->choices[0]->message->content;
        } catch (Exception $e) {
            Log::error('Erreur OpenAI: ' . $e->getMessage());
            throw new Exception('Impossible de générer le contenu de formation: ' . $e->getMessage());
        }
    }

    private function buildPrompt($topic, $details, $level)
    {
        return "Créez un plan de formation complet sur le sujet: {$topic}. 
                Niveau: {$level}.
                Détails supplémentaires: {$details}.
                
                Le plan doit inclure:
                1. Un titre accrocheur pour la formation
                2. Une description générale (environ 150 mots)
                3. 3-5 modules avec leurs titres
                4. Pour chaque module, 2-3 leçons avec titres et descriptions courtes
                5. Pour chaque module, suggérer un exercice pratique ou quiz
                
                Format en JSON pour faciliter l'intégration dans l'application:
                {
                    \"titre\": \"Titre de la formation\",
                    \"description\": \"Description générale\",
                    \"modules\": [
                        {
                            \"titre\": \"Titre du module 1\",
                            \"description\": \"Description du module\",
                            \"lecons\": [
                                {
                                    \"titre\": \"Titre de la leçon 1\",
                                    \"contenu\": \"Description de la leçon\"
                                }
                            ],
                            \"quiz\": {
                                \"titre\": \"Quiz du module\",
                                \"questions\": [
                                    {
                                        \"contenu\": \"Question 1?\",
                                        \"reponses\": [
                                            {\"contenu\": \"Réponse 1\", \"est_correcte\": true},
                                            {\"contenu\": \"Réponse 2\", \"est_correcte\": false}
                                        ]
                                    }
                                ]
                            }
                        }
                    ]
                }";
    }

    public function streamFormationContent($topic, $details = '', $level = 'intermédiaire')
    {
        try {
            $prompt = $this->buildPrompt($topic, $details, $level);
            
            $stream = $this->client->chat()->createStreamed([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'Vous êtes un expert en création de formations éducatives. Vous devez créer des contenus structurés, pédagogiques et détaillés.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 4000,
            ]);

            foreach ($stream as $response) {
                if (isset($response->choices[0]->delta->content)) {
                    yield $response->choices[0]->delta->content;
                }
            }
        } catch (Exception $e) {
            Log::error('Erreur OpenAI Streaming: ' . $e->getMessage());
            yield "ERREUR: " . $e->getMessage();
        }
    }
}