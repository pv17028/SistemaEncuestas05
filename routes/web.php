<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EncuestaController;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/encuestas', [EncuestaController::class, 'index'])->name('encuestas.index');
    Route::get('/encuestas/create', [EncuestaController::class, 'create'])->name('encuestas.create');
    Route::post('/encuestas', [EncuestaController::class, 'store'])->name('encuestas.store');
    Route::get('/encuestas/{encuesta}', [EncuestaController::class, 'show'])->name('encuestas.show');
    Route::get('/encuestas/{encuesta}/edit', [EncuestaController::class, 'edit'])->name('encuestas.edit');
    Route::put('/encuestas/{encuesta}', [EncuestaController::class, 'update'])->name('encuestas.update');
    Route::delete('/encuestas/{encuesta}', [EncuestaController::class, 'destroy'])->name('encuestas.destroy');
});
