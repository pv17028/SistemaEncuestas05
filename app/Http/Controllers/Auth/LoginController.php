<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'correoElectronico';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'correoElectronico' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        // Si el inicio de sesión es exitoso, establece ultimaAcceso a la fecha y hora actuales
        $request->user()->ultimaAcceso = now();
        $request->user()->intentosFallidos = 0; // resetea los intentos fallidos
        $request->user()->save();

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        // Si el inicio de sesión falla, incrementa intentosFallidos
        $user = User::where('correoElectronico', $request->correoElectronico)->first();
        if ($user) {
            $user->intentosFallidos = ($user->intentosFallidos ?? 0) + 1;
            $user->save();
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
