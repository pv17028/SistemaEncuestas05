<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Privilegio;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Muestra una lista de roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Rol::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Muestra el formulario para crear un nuevo rol.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $privilegios = Privilegio::all();
        return view('roles.create', compact('privilegios'));
    }

    /**
     * Almacena un nuevo rol en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombreRol' => 'required',
            'descripcionRol' => 'required',
        ]);

        Rol::create($request->all());

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar el rol especificado.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit(Rol $rol)
    {
        $privilegios = Privilegio::all();
        return view('roles.edit', compact('rol', 'privilegios'));
    }

    /**
     * Actualiza el rol especificado en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rol $rol)
    {
        $request->validate([
            'nombreRol' => 'required',
            'descripcionRol' => 'required',
        ]);

        $rol->update($request->all());

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Muestra el rol especificado.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        $rol = Rol::find(1); // Encuentra el rol con id 1
        $privilegios = $rol->privilegios; // Accede a los privilegios del rol
        return view('roles.show', compact('rol'));
    }

    /**
     * Elimina el rol especificado de la base de datos.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }
}
