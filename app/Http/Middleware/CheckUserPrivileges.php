<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserPrivileges
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user()->load('role');
        $routeName = $request->route()->getName();
    
        // Comprueba si el usuario tiene el privilegio necesario
        if (!$user->hasPrivilege($routeName)) {
            // Si el usuario no tiene el privilegio, redirige a una página de error
            return redirect('error');
        }
    
        return $next($request);
    }
}