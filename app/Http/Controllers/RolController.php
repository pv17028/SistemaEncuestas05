<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Privilegio;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

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
        $privilegios = Privilegio::all()->groupBy(function($privilegio) {
            return explode('.', $privilegio->url)[0];
        });
    
        $nombresModulos = [
            'encuestas' => 'Encuestas',
            'roles' => 'Generación de Roles',
            'privilegios' => 'Generación de Privilegios',
            'users' => 'Gestión de Usuarios',
            'profile' => 'Perfil',
            'bloqueos' => 'Gestión de Bloqueos',
            'gestionEncuestas' => 'Gestión de Encuestas',
            'tiposPreguntas' => 'Gestión de Encuestas - Tipos de Preguntas',
            'preguntas' => 'Preguntas',
            'ecompartidas' => 'Encuestas Compartidas',
            'resultadoEncuesta' => 'Resultados de Encuestas',
            'exportacion' => 'Exportación de Datos',
        ];
    
        return view('roles.create', ['privilegios' => $privilegios, 'nombresModulos' => $nombresModulos]);
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
            'privilegios' => 'required|array', // Asegúrate de que se proporcionen privilegios
        ]);
    
        $rol = Rol::create($request->only(['nombreRol', 'descripcionRol'])); // Crea el rol
    
        $rol->privilegios()->sync($request->privilegios); // Asigna los privilegios al rol
    
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
        $privilegios = Privilegio::all()->groupBy(function($privilegio) {
            return explode('.', $privilegio->url)[0];
        });
    
        $nombresModulos = [
            'encuestas' => 'Encuestas',
            'roles' => 'Generación de Roles',
            'privilegios' => 'Generación de Privilegios',
            'users' => 'Gestión de Usuarios',
            'profile' => 'Perfil',
            'bloqueos' => 'Gestión de Bloqueos',
            'gestionEncuestas' => 'Gestión de Encuestas',
            'tiposPreguntas' => 'Gestión de Encuestas - Tipos de Preguntas',
            'preguntas' => 'Preguntas',
            'ecompartidas' => 'Encuestas Compartidas',
            'resultadoEncuesta' => 'Resultados de Encuestas',
            'exportacion' => 'Exportación de Datos',
        ];
    
        return view('roles.edit', ['rol' => $rol, 'privilegios' => $privilegios, 'nombresModulos' => $nombresModulos]);
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
            'privilegios' => 'required|array',
        ]);
    
        $rol->update($request->only('nombreRol', 'descripcionRol'));
    
        // Actualiza los privilegios del rol
        $rol->privilegios()->sync($request->privilegios);
    
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
        $privilegios = $rol->privilegios; // Accede a los privilegios del rol
        return view('roles.show', compact('rol', 'privilegios'));
    }

    /**
     * Elimina el rol especificado de la base de datos.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        try {
        // Desasociar todos los privilegios antes de eliminar el rol
        $rol->privilegios()->detach();
    
        $rol->delete();
    
        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
        } catch (QueryException $e) {
        // Código de error para violación de restricción de clave foránea en PostgreSQL
        $foreignKeyViolationCode = '23503';
    
        if ($e->getCode() === $foreignKeyViolationCode) {
            return redirect()->route('roles.index')
            ->with('warning', 'No se puede eliminar el rol porque está asociado a uno o más usuarios.');
        }
    
        throw $e;
        }
    }
}
