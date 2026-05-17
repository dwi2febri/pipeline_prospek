<?php

return [

    'default' => 'gemini',

    'providers' => [

        'gemini' => [
            'driver' => 'gemini',
            'key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
        ],

        'openai' => [
            'driver' => 'openai',
            'key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        ],

        'ollama' => [
            'driver' => 'ollama',
            'key' => env('OLLAMA_API_KEY'),
            'model' => env('OLLAMA_MODEL', 'llama3.1'),
            'url' => env('OLLAMA_URL', 'http://127.0.0.1:11434'),
        ],

    ],

];
