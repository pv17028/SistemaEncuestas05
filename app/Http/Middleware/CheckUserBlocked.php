<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CheckUserBlocked
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Si el usuario está autenticado y tiene registros de bloqueo, verificar cada uno
        if ($user && $user->bloqueosUsuario) {
            foreach ($user->bloqueosUsuario as $bloqueoUsuario) {
                if ($bloqueoUsuario->status == 'blocked') {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'correoElectronico' => [trans('auth.account_locked')],
                    ]);
                    break; // Detener la iteración
                }
            }
        }

        return $next($request);
    }
}