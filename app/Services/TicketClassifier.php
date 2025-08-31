<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class TicketClassifier
{
    public function classify(string $subject, string $body): array
    {
        if (!config('openai.classify_enabled', true)) {
            return [
                'category' => fake()->randomElement(['billing', 'technical', 'account']),
                'explanation' => 'Dummy explanation (AI disabled)',
                'confidence' => fake()->randomFloat(2, 0.5, 0.95),
            ];
        }

        $prompt = <<<EOT
You are an AI support classifier. 
Classify the following ticket into one category. 
Return ONLY valid JSON with keys: category, explanation, confidence (0-1 float).

Ticket:
Subject: {$subject}
Body: {$body}
EOT;

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You classify tickets into categories: billing, technical, account, other. Always respond in JSON.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $raw = $response->choices[0]->message->content;

        $data = json_decode($raw, true);

        if (!is_array($data) || !isset($data['category'])) {

            return [
                'category' => 'other',
                'explanation' => 'Could not parse AI output',
                'confidence' => 0.0,
            ];
        }

        return $data;
    }
}
