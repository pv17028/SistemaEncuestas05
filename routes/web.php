<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoPreguntaController;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BloqueoUsuarioController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckUserBlocked;
use App\Http\Controllers\PreguntasController;
use App\Http\Controllers\OpcionController;
Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware(\App\Http\Middleware\CheckUserBlocked::class);

Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth', CheckUserBlocked::class])->group(function () {
    Route::get('/encuestas', [EncuestaController::class, 'index'])->name('encuestas.index');
    Route::get('/encuestas/create', [EncuestaController::class, 'create'])->name('encuestas.create');
    Route::post('/encuestas', [EncuestaController::class, 'store'])->name('encuestas.store');
    Route::get('/encuestas/{encuesta}', [EncuestaController::class, 'show'])->name('encuestas.show');
    Route::get('/encuestas/{encuesta}/edit', [EncuestaController::class, 'edit'])->name('encuestas.edit');
    Route::put('/encuestas/{encuesta}', [EncuestaController::class, 'update'])->name('encuestas.update');
    Route::delete('/encuestas/{encuesta}', [EncuestaController::class, 'destroy'])->name('encuestas.destroy');
});

Route::middleware(['auth', AdminMiddleware::class, CheckUserBlocked::class])->group(function () {
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{rol}', [RolController::class, 'show'])->name('roles.show');
    Route::get('/roles/{rol}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{rol}', [RolController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{rol}', [RolController::class, 'destroy'])->name('roles.destroy');
});

Route::middleware(['auth', AdminMiddleware::class, CheckUserBlocked::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', CheckUserBlocked::class])->group(function () {
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware(['auth', AdminMiddleware::class, CheckUserBlocked::class])->group(function () {
    Route::get('/bloqueos', [BloqueoUsuarioController::class, 'index'])->name('bloqueos.index');
    Route::get('/bloqueos/create', [BloqueoUsuarioController::class, 'create'])->name('bloqueos.create');
    Route::post('/bloqueos', [BloqueoUsuarioController::class, 'store'])->name('bloqueos.store');
    Route::get('/bloqueos/{bloqueo}', [BloqueoUsuarioController::class, 'show'])->name('bloqueos.show');
    Route::get('/bloqueos/{bloqueo}/edit', [BloqueoUsuarioController::class, 'edit'])->name('bloqueos.edit');
    Route::put('/bloqueos/{bloqueo}', [BloqueoUsuarioController::class, 'update'])->name('bloqueos.update');
    Route::delete('/bloqueos/{bloqueo}', [BloqueoUsuarioController::class, 'destroy'])->name('bloqueos.destroy');
    Route::put('/bloqueos/{bloqueo}/desbloquear', [BloqueoUsuarioController::class, 'desbloquear'])->name('bloqueos.desbloquear');
});


    Route::get('/tiposPreguntas', [TipoPreguntaController::class, 'index'])->name('tiposPreguntas.index');
    Route::get('/tiposPreguntas/create', [TipoPreguntaController::class, 'create'])->name('tiposPreguntas.create');
    Route::post('/tiposPreguntas', [TipoPreguntaController::class, 'store'])->name('tiposPreguntas.store');
    Route::get('/tiposPreguntas/{tipoPregunta}', [TipoPreguntaController::class, 'show'])->name('tiposPreguntas.show');
    Route::get('/tiposPreguntas/{tipoPregunta}/edit', [TipoPreguntaController::class, 'edit'])->name('tiposPreguntas.edit');
    Route::put('/tiposPreguntas/{tipoPregunta}', [TipoPreguntaController::class, 'update'])->name('tiposPreguntas.update');
    Route::delete('/tiposPreguntas/{tipoPregunta}', [TipoPreguntaController::class, 'destroy'])->name('tiposPreguntas.destroy');

    Route::get('/preguntas', [PreguntasController::class, 'index'])->name('preguntas.index');
    Route::get('/preguntas/create', [PreguntasController::class, 'create'])->name('preguntas.create');
    Route::post('/preguntas', [PreguntasController::class, 'store'])->name('preguntas.store');
    Route::get('/preguntas/{preguntas}', [PreguntasController::class, 'show'])->name('preguntas.show');
    Route::get('/preguntas/{preguntas}/edit', [PreguntasController::class, 'edit'])->name('preguntas.edit');
    Route::put('/preguntas/{preguntas}', [PreguntasController::class, 'update'])->name('preguntas.update');
    Route::delete('/preguntas/{preguntas}', [PreguntasController::class, 'destroy'])->name('preguntas.destroy');

    // Rutas anidadas para opciones dentro de preguntas
    Route::prefix('preguntas/{preguntas}')->group(function () {
        Route::get('opciones', [OpcionController::class, 'index'])->name('preguntas.opciones.index');
        Route::get('opciones/create', [OpcionController::class, 'create'])->name('preguntas.opciones.create');
        Route::post('opciones', [OpcionController::class, 'store'])->name('preguntas.opciones.store');
        Route::get('opciones/{opcion}/edit', [OpcionController::class, 'edit'])->name('preguntas.opciones.edit');
        Route::put('opciones/{opcion}', [OpcionController::class, 'update'])->name('preguntas.opciones.update');
        Route::delete('opciones/{opcion}', [OpcionController::class, 'destroy'])->name('preguntas.opciones.destroy');
    });
    