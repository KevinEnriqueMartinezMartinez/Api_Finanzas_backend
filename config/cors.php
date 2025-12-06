<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |---------------------------------------------------------------------------
    |
    | Aquí puedes configurar tus ajustes para el intercambio de recursos 
    | entre orígenes (CORS). Esto determina qué operaciones entre orígenes 
    | pueden ejecutarse en los navegadores web.
    |
    | Para aprender más: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'clientes', 'sanctum/csrf-cookie'],  // Incluye la ruta 'clientes' aquí

    'allowed_methods' => ['*'],  // Permitir todos los métodos

    'allowed_origins' => ['http://localhost:4200'],  // Origen permitido, el de tu frontend Angular

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Permitir todos los encabezados

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];

