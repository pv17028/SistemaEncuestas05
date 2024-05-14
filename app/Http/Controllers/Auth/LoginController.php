<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Models\BloqueoUsuario;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountBlocked;

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

    public function login(Request $request)
    {
        // Buscar usuario por correo electrónico
        $user = User::where('correoElectronico', $request->correoElectronico)->first();

        // Si el usuario está autenticado y tiene registros de bloqueo, verificar cada uno
        if ($user && $user->bloqueosUsuario) {
            foreach ($user->bloqueosUsuario as $bloqueoUsuario) {
                if ($bloqueoUsuario->status == 'blocked') {
                    Auth::logout();
                    $subject = 'Solicitud de Desbloqueo de Cuenta';
                    $bodyIntro = 'Estimado equipo de soporte,';
                    $bodyMain = 'Mi cuenta asociada al correo electrónico: ' . e($user->correoElectronico) . ', y al nombre de usuario: ' . e($user->username) . ', parece estar bloqueada. Solicito formalmente la revisión y el desbloqueo de mi cuenta.';
                    $bodyLink = 'Puedes ver los detalles del bloqueo en el siguiente enlace: ' . e(url('/bloqueos/' . $bloqueoUsuario->id)) . '.';
                    $bodyThanks = 'Agradezco de antemano su atención a esta solicitud.';
                    $bodySalutation = 'Saludos cordiales,';
                    $bodySignature = e($user->nombre) . ' ' . e($user->apellido) . '.';
                    $linkText = 'Por favor, haga clic aquí para enviar una solicitud de desbloqueo.';

                    throw ValidationException::withMessages([
                        'correoElectronico' => [
                            new HtmlString(
                                trans('auth.account_locked') . ' ' .
                                '<a href="mailto:surveyprosv@gmail.com?subject=' . $subject . '&body=' . $bodyIntro . '%0D%0A%0D%0A' . $bodyMain . '%0D%0A%0D%0A' . $bodyLink . '%0D%0A%0D%0A' . $bodyThanks . '%0D%0A%0D%0A' . $bodySalutation . '%0D%0A' . $bodySignature . '">' . $linkText . '</a>'
                            )
                        ],
                    ]);
                    break; // Detener la iteración
                } else if ($bloqueoUsuario->status == 'temporarily_blocked') {
                    $blockedUntil = Carbon::parse($bloqueoUsuario->blocked_until);
                    if ($blockedUntil->greaterThan(now())) {
                        Auth::logout();
                        throw ValidationException::withMessages([
                            'correoElectronico' => [trans('auth.user_blocked')],
                        ]);
                        break; // Detener la iteración
                    }
                }
            }
        }

        // Intentar iniciar sesión
        if (Auth::attempt(['correoElectronico' => $request->correoElectronico, 'password' => $request->password])) {
            // Autenticación exitosa, redirigir al home
            return redirect()->intended('/');
        } else {
            // Si el inicio de sesión falla, incrementa intentosFallidos
            if ($user) {
                // Si el usuario es un administrador, no incrementar intentosFallidos ni bloquear
                if ($user->role == 'admin') {
                    throw ValidationException::withMessages([
                        $this->username() => [trans('auth.failed')],
                    ]);
                }

                $user->intentosFallidos = ($user->intentosFallidos ?? 0) + 1;
                $user->save();

                Log::info('Intento de inicio de sesión fallido', ['user_id' => $user->id, 'attempt_count' => $user->intentosFallidos]);

                // Si el usuario ha fallado 3 intentos de inicio de sesión
                if ($user->intentosFallidos >= 3) {
                    // Registrar un bloqueo temporal
                    $bloqueoUsuario = new BloqueoUsuario; // Crear un nuevo registro de bloqueo
                    $bloqueoUsuario->user_id = $user->id;
                    $bloqueoUsuario->blocked_at = now(); // establecer blocked_at al tiempo actual
                    $bloqueoUsuario->status = 'temporarily_blocked'; // bloqueo "temporal"
                    $bloqueoUsuario->blocked_until = now()->addMinutes(3); // bloqueado por 1 minuto
                    $bloqueoUsuario->unblocked_at = now()->addMinutes(3); // desbloqueado después de 1 minuto
                    $bloqueoUsuario->block_duration = 180; // duración del bloqueo en segundos
                    $bloqueoUsuario->temp_blocks = 1; // bloqueo "temporal"
                    $bloqueoUsuario->reason = 'Demasiados intentos de inicio de sesión fallidos'; // establecer reason
                    $bloqueoUsuario->save();

                    // Verificar si los últimos dos bloqueos del usuario fueron temporales
                    $lastTwoBlocks = BloqueoUsuario::where('user_id', $user->id)
                        ->orderBy('blocked_at', 'desc')
                        ->take(2)
                        ->get();

                    if ($lastTwoBlocks->count() == 2 && $lastTwoBlocks->every(function ($block) {
                        return $block->status == 'temporarily_blocked';
                    })) {
                        // Si los últimos dos bloqueos fueron temporales, bloquear la cuenta
                        $bloqueoUsuario->status = 'blocked'; // bloqueo "permanente"
                        $bloqueoUsuario->temp_blocks = 2; // bloqueo "permanente" 
                        $bloqueoUsuario->reason = 'Demasiados intentos de inicio de sesión fallidos'; // actualizar reason
                        $bloqueoUsuario->blocked_until = null; // no hay fecha de desbloqueo programada
                        $bloqueoUsuario->unblocked_at = null; // no hay fecha de desbloqueo programada
                        $bloqueoUsuario->block_duration = null; // bloqueo es indefinido
                        $bloqueoUsuario->save();

                        // Send the email.
                        Mail::to($user->correoElectronico)->send(new AccountBlocked($user, $bloqueoUsuario));

                        Log::info('Cuenta de usuario bloqueada debido a demasiados bloqueos temporales', ['user_id' => $user->id]);

                        throw ValidationException::withMessages([
                            $this->username() => [trans('auth.account_locked')],
                        ]);
                    } else {
                        // Restablecer intentosFallidos
                        $user->intentosFallidos = 0;
                        $user->save();

                        Log::info('Usuario bloqueado temporalmente debido a demasiados intentos de inicio de sesión fallidos', ['user_id' => $user->id]);

                        throw ValidationException::withMessages([
                            $this->username() => [trans('auth.too_many_attempts')],
                        ]);
                    }
                }
            }

            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
    }
}
