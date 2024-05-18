<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Rol;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'correoElectronico' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'fechaNacimiento' => ['required', 'date'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Obtén el ID del rol de usuario de la base de datos
        $roleId = Rol::where('nombreRol', 'usuario')->first()->idRol;

        $user = User::create([
            'idRol' => $roleId,
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'correoElectronico' => $data['correoElectronico'],
            'fechaNacimiento' => $data['fechaNacimiento'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'intentosFallidos' => 0,
        ]);

        return $user;
    }
}
