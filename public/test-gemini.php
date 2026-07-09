<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$apiKey = trim(env('GEMINI_API_KEY'));
echo "Key starts with: " . substr($apiKey, 0, 5) . "\n";
echo "Key length: " . strlen($apiKey) . "\n";

$ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'contents' => [
        [
            'parts' => [
                ['text' => 'Hello']
            ]
        ]
    ]
]));
$response = curl_exec($ch);
echo "Response: " . $response . "\n";
