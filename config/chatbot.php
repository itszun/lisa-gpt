<?php


return [
    'access_key' => env("CHATBOT_ACCESS_KEY", ""),
    'host' => env("CHATBOT_HOST", 'http://localhost:5000'),
    'endpoint' => env("CHATBOT_HOST", 'http://localhost:5000')."/".env("CHATBOT_ENDPOINT", 'api/chat'),
];
