<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloqueoUsuario;
use App\Models\User;
use Carbon\Carbon;
use App\Mail\AccountUnlocked;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AccountBlocked;

class BloqueoUsuarioController extends Controller
{
    // public function index()
    // {
    //     $bloqueos = BloqueoUsuario::all();
    //     return view('bloqueos.index', compact('bloqueos'));
    // }

    public function index()
    {
        $bloqueos = BloqueoUsuario::whereIn('status', ['blocked', 'active'])->get();
        return view('bloqueos.index', compact('bloqueos'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('role', function ($query) {
            $query->where('nombreRol', 'admin');
        })->whereDoesntHave('bloqueosUsuario', function ($query) {
            $query->where('status', 'blocked');
        })->get();

        return view('bloqueos.create', compact('users')); // Pasar los usuarios a la vista
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string',
        ]);

        $bloqueo = BloqueoUsuario::create([
            'user_id' => $request->user_id,
            'reason' => $request->reason,
            'blocked_at' => now(),
            'status' => 'blocked',
        ]);

        // Send the email.
        $user = User::find($request->user_id);
        Mail::to($user->correoElectronico)->send(new AccountBlocked($user, $bloqueo));

        return redirect()->route('bloqueos.index')->with('success', 'Bloqueo creado con éxito');
    }

    public function show(BloqueoUsuario $bloqueo)
    {
        return view('bloqueos.show', compact('bloqueo'));
    }

    public function edit($id)
    {
        $bloqueo = BloqueoUsuario::find($id);
        $razones = BloqueoUsuario::select('reason')->distinct()->get();
        return view('bloqueos.edit', compact('bloqueo', 'razones'));
    }

    public function update(Request $request, BloqueoUsuario $bloqueo)
    {
        $bloqueo->update($request->all());

        if ($bloqueo->status == 'active') {
            // The block status has changed to active. Send the email.
            Mail::to($bloqueo->user->correoElectronico)->send(new AccountUnlocked($bloqueo->user));
        }

        return redirect()->route('bloqueos.index')->with('success', 'Bloqueo actualizado con éxito');
    }

    public function destroy(BloqueoUsuario $bloqueo)
    {
        $bloqueo->delete();
        return redirect()->route('bloqueos.index')->with('success', 'Bloqueo eliminado con éxito');
    }

    public function desbloquear($id)
    {
        $bloqueo = BloqueoUsuario::findOrFail($id);
        $bloqueo->user->update(['bloqueado' => false]);

        // Calcular la duración del bloqueo
        $blockedAt = Carbon::parse($bloqueo->blocked_at);
        $block_duration = round($blockedAt->diffInSeconds(now()));

        $bloqueo->update([
            'unblocked_at' => now(), // Agregar la fecha de desbloqueo
            'status' => 'active', // Cambiar el estado a 'active'
            'block_duration' => $block_duration, // Agregar la duración del bloqueo
        ]);

        // Enviar correo electrónico al usuario
        Log::info('Enviando correo a: ' . $bloqueo->user->correoElectronico);
        Mail::to($bloqueo->user->correoElectronico)->send(new AccountUnlocked($bloqueo->user));

        return redirect()->route('bloqueos.index')->with('success', 'Usuario desbloqueado con éxito');
    }
}
