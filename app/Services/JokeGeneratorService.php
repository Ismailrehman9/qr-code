<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class JokeGeneratorService
{
    public function generate(string $name, string $ageBracket): string
    {
        try {
            $prompt = "Generate a short, witty, and personalized joke for {$name} who is in the {$ageBracket} age bracket. " .
                     "The joke should be theatre/show themed, family-friendly, clever, and make them smile. " .
                     "Keep it under 150 characters. Start with 'Hey {$name},'";

            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a witty comedian who creates personalized, clean jokes for theatre audiences.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 100,
                'temperature' => 0.9,
            ]);

            $joke = $result->choices[0]->message->content;
            return trim($joke);

        } catch (\Exception $e) {
            \Log::error('Joke generation failed: ' . $e->getMessage());
            
            // Fallback jokes
            $fallbackJokes = [
                "Hey {$name}, you're at the perfect age to enjoy both the show and the snacks! 🎭",
                "Hey {$name}, thanks for joining us! May your seat be comfy and your laughter be loud! 🎪",
                "Hey {$name}, you've got great taste in entertainment... and giveaways! 😄",
                "Hey {$name}, welcome! Remember: life's a stage, and you've got front row seats! 🌟",
                "Hey {$name}, you're here for the show, the laughs, and maybe the free stuff! 🎁",
            ];

            return $fallbackJokes[array_rand($fallbackJokes)];
        }
    }

    public function generateBatch(array $participants): array
    {
        $jokes = [];
        
        foreach ($participants as $participant) {
            $jokes[$participant['id']] = $this->generate(
                $participant['name'],
                $participant['age_bracket']
            );
        }

        return $jokes;
    }
}
