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
            "You are a wise numerologist. Provide a short, uplifting numerology reading for a person who is %d years old. " .
            "The reading should be positive, encouraging, and suitable for a general audience at a live event. " .
            "Focus on the life path or universal year themes associated with their age. " .
            "Return the reading in a single, concise sentence without any additional commentary. " .
            "The response should be between 200 and 250 characters.",
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
            $reading = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($reading) {
                Log::info('Gemini numerology reading generated', [
                    'model' => $modelName,
                    'age' => $age,
                    'reading' => $reading,
                    'is_fallback' => $isFallback,
                ]);
                return $reading;
            }

            Log::warning('Gemini response did not include numerology reading text', [
                'model' => $modelName,
                'age' => $age,
                'response' => $data,
            ]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Gemini numerology reading generation failed: ' . $e->getMessage(), [
                'model' => $modelName,
                'age' => $age,
                'is_fallback' => $isFallback,
                'exception' => $e,
            ]);
            return null;
        }
    }
}

