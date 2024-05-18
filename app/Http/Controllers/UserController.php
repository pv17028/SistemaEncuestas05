<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\BloqueoUsuario;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Rol::all();
        $bloqueos = BloqueoUsuario::all();
        return view('users.create', compact('roles', 'bloqueos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'correoElectronico' => 'required|email|unique:users,correoElectronico',
            'fechaNacimiento' => 'required|date',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'password' => 'sometimes|required',
            // otras reglas de validación...
        ]);

        $user = new User;
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->correoElectronico = $request->correoElectronico;
        $user->fechaNacimiento = $request->fechaNacimiento;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->intentosFallidos = $request->intentosFallidos;
        $user->ultimaAcceso = $request->ultimaAcceso;
        $user->idRol = $request->idRol;
        $user->idBloqueUsuario = $request->idBloqueUsuario;
        $user->save();

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Rol::all();
        $bloqueos = BloqueoUsuario::all();
        return view('users.edit', compact('user', 'roles', 'bloqueos'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'correoElectronico' => 'required|email|unique:users,correoElectronico,' . $user->id,
            'fechaNacimiento' => 'required|date',
            'username' => 'required|unique:users,username,' . $user->id,
            'password' => 'nullable|confirmed',
            // otras reglas de validación...
        ]);

        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->correoElectronico = $request->correoElectronico;
        $user->fechaNacimiento = $request->fechaNacimiento;
        $user->username = $request->username;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->intentosFallidos = $request->intentosFallidos;
        $user->ultimaAcceso = $request->ultimaAcceso;
        $user->idRol = $request->idRol;
        $user->idBloqueUsuario = $request->idBloqueUsuario;
        $user->save();

        return redirect()->route('users.index');
    }

    // public function destroy(User $user)
    // {
    //     // Elimina los bloqueos del usuario
    //     foreach ($user->bloqueosUsuario as $bloqueo) {
    //         $bloqueo->delete();
    //     }

    //     // Ahora puedes eliminar el usuario
    //     $user->delete();

    //     return redirect()->route('users.index');
    // }

    public function destroy(User $user)
    {
        // Desvincula los bloqueos del usuario
        foreach ($user->bloqueosUsuario as $bloqueo) {
            $bloqueo->username_historico = $user->username;
            $bloqueo->nombre_historico = $user->nombre;
            $bloqueo->apellido_historico = $user->apellido;
            $bloqueo->email_historico = $user->correoElectronico;
            $bloqueo->user_id = null;
            $bloqueo->save();
        }

        // Ahora puedes eliminar el usuario
        $user->delete();

        return redirect()->route('users.index');
    }

    public function showProfile()
    {
        return view('profile.show');
    }

    public function editProfile()
    {
        return view('profile.edit');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correoElectronico' => 'required|string|email|max:255|unique:users,correoElectronico,' . auth()->id(),
            'fechaNacimiento' => 'required|date',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->fill($request->except('password'));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado con éxito');
    }
}
