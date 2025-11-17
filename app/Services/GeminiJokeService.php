<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiJokeService
{
    protected ?string $apiKey;
    protected string $model;
    protected array $fallbackModels;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash-lite');
        $this->fallbackModels = config('services.gemini.fallback_models', [
            'gemini-2.0-flash',
            'gemini-2.5-flash',
        ]);
    }

    public function generateForAge(int $age): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key is missing. Set GEMINI_API_KEY in .env');
            return null;
        }

        $prompt = sprintf(
            "You are a friendly comedian. Create one short, playful joke suitable for someone who is %d years old attending a live giveaway show. " .
            "The joke must be family-friendly, avoid age-shaming, and feel uplifting for theatre audiences. " .
            "Return the joke in a single sentence without additional commentary.",
            $age
        );

        $modelsToTry = array_values(array_unique(array_merge([$this->model], $this->fallbackModels)));

        foreach ($modelsToTry as $index => $modelName) {
            $result = $this->requestGemini($prompt, $modelName, $age, $index > 0);
            if ($result !== null) {
                return $result;
            }
        }

        Log::error('Gemini failed for all configured models', [
            'models_tried' => $modelsToTry,
            'age' => $age,
        ]);

        return null;
    }

    private function requestGemini(string $prompt, string $modelName, int $age, bool $isFallback): ?string
    {
        $url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $modelName,
            $this->apiKey
        );

        try {
            $response = Http::timeout(10)->post($url, [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature' => 0.85,
                    'maxOutputTokens' => 120,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_ONLY_HIGH',
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_ONLY_HIGH',
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_ONLY_HIGH',
                    ],
                ],
            ]);

            if ($response->failed()) {
                $status = $response->status();

                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'model' => $modelName,
                    'age' => $age,
                    'is_fallback' => $isFallback,
                ]);

                if (!$isFallback && in_array($status, [429, 503]) && count($this->fallbackModels) > 0) {
                    Log::notice('Gemini primary model throttled, attempting fallback', [
                        'failed_model' => $modelName,
                        'age' => $age,
                        'status' => $status,
                    ]);
                }

                return null;
            }

            $data = $response->json();
            $joke = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($joke) {
                Log::info('Gemini joke generated', [
                    'model' => $modelName,
                    'age' => $age,
                    'joke' => $joke,
                    'is_fallback' => $isFallback,
                ]);
                return $joke;
            }

            Log::warning('Gemini response did not include joke text', [
                'model' => $modelName,
                'age' => $age,
                'response' => $data,
            ]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Gemini joke generation failed: ' . $e->getMessage(), [
                'model' => $modelName,
                'age' => $age,
                'is_fallback' => $isFallback,
                'exception' => $e,
            ]);
            return null;
        }
    }
}

