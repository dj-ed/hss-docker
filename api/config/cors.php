<?php

$allowedOrigins = [env('APP_URL')];

if(env('APP_ENV') == 'local'){
    $allowedOrigins = ['http://localhost:8080', 'http://localhost:4000', 'http://localhost:4040'];
}

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */

    'supportsCredentials' => true,
    'allowedOrigins' => $allowedOrigins,
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['GET', 'POST', 'OPTIONS'],
    'exposedHeaders' => [],
    'maxAge' => 3600,

];
