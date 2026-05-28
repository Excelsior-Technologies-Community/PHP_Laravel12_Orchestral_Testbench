<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Greeting Suffix
    |--------------------------------------------------------------------------
    |
    | This suffix is appended to every greeting message.
    |
    */
    'suffix' => env('GREETING_SUFFIX', 'Welcome to our application!'),
    
    /*
    |--------------------------------------------------------------------------
    | Cache TTL (seconds)
    |--------------------------------------------------------------------------
    |
    | Default cache time-to-live for greetings.
    |
    */
    'cache_ttl' => env('GREETING_CACHE_TTL', 3600),
];