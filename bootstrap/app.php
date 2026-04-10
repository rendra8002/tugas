<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware lo di sini
        $middleware->alias([
            'admin.petugas' => \App\Http\Middleware\IsAdminOrPetugas::class,
            'anggota' => \App\Http\Middleware\IsAnggota::class,
            'petugas.only' => \App\Http\Middleware\CheckPetugasOnly::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
