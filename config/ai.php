<?php

use Laravel\Ai\Enums\Lab;

return [

    'default' => Lab::Gemini,

    'providers' => [

        'gemini' => [
            'driver' => 'gemini',
            'key' => env('GEMINI_API_KEY'),
        ],

        'openai' => [
            'driver' => 'openai',
            'key' => env('OPENAI_API_KEY'),
        ],

        'ollama' => [
            'driver' => 'ollama',
            'key' => env('OLLAMA_API_KEY'),
        ],

    ],

    'models' => [
        'text' => 'gemini-2.5-flash',
        'embeddings' => 'text-embedding-004',
    ],

];
