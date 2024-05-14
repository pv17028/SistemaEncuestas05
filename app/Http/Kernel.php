<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Aquí se enumeran los middlewares globales
    ];

    protected $middlewareGroups = [
        'web' => [
            // Aquí se enumeran los middlewares para el grupo 'web'
            \App\Http\Middleware\CheckUserBlocked::class, // Agregar esto
        ],

        'api' => [
            // Aquí se enumeran los middlewares para el grupo 'api'
        ],
    ];

    protected $routeMiddleware = [
        // Aquí se enumeran los middlewares que se pueden asignar a rutas individuales
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];
}