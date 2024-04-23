<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use Illuminate\Http\Request;

class EncuestaController extends Controller
{
    // Mostrar todas las encuestas
    public function index()
    {
        $idUsuario = auth()->user()->id; // Obtener el ID del usuario autenticado
        $encuestas = Encuesta::where('idUsuario', $idUsuario)->get(); // Filtrar las encuestas por el ID del usuario
        return view('encuestas.index', compact('encuestas'));
    }

    // Mostrar el formulario para crear una nueva encuesta
    public function create()
    {
        return view('encuestas.create');
    }

    // Almacenar una nueva encuesta
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'objetivo' => 'required|string',
            'descripcionEncuesta' => 'required|string',
            'grupoMeta' => 'required|string|max:255',
            'fechaVencimiento' => 'required|date',
        ]);

        // Crear la encuesta con el idUsuario del usuario autenticado
        $encuesta = new Encuesta;
        $encuesta->titulo = $request->titulo;
        $encuesta->objetivo = $request->objetivo;
        $encuesta->descripcionEncuesta = $request->descripcionEncuesta;
        $encuesta->grupoMeta = $request->grupoMeta;
        $encuesta->fechaVencimiento = $request->fechaVencimiento;
        $encuesta->idUsuario = $request->idUsuario; // Asignar el idUsuario del formulario
        $encuesta->save();

        return redirect()->route('encuestas.index')->with('success', 'Encuesta creada correctamente.');
    }

    // Mostrar una encuesta específica
    public function show(Encuesta $encuesta)
    {
        // Verificar si la encuesta pertenece al usuario autenticado
        if ($encuesta->idUsuario != auth()->user()->id) {
            abort(403, 'No tienes permiso para ver esta encuesta.');
        }

        return view('encuestas.show', compact('encuesta'));
    }

    // Mostrar el formulario para editar una encuesta
    public function edit(Encuesta $encuesta)
    {
        // Verificar si la encuesta pertenece al usuario autenticado
        if ($encuesta->idUsuario != auth()->user()->id) {
            abort(403, 'No tienes permiso para editar esta encuesta.');
        }

        return view('encuestas.edit', compact('encuesta'));
    }

    // Actualizar una encuesta existente
    public function update(Request $request, Encuesta $encuesta)
    {
        // Validación de datos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'objetivo' => 'required|string',
            'descripcionEncuesta' => 'required|string',
            'grupoMeta' => 'required|string|max:255',
            'fechaVencimiento' => 'required|date',
        ]);

        // Actualizar la encuesta con los datos del formulario
        $encuesta->update([
            'titulo' => $request->titulo,
            'objetivo' => $request->objetivo,
            'descripcionEncuesta' => $request->descripcionEncuesta,
            'grupoMeta' => $request->grupoMeta,
            'fechaVencimiento' => $request->fechaVencimiento,
        ]);

        return redirect()->route('encuestas.index')->with('success', 'Encuesta actualizada correctamente.');
    }

    // Eliminar una encuesta existente
    public function destroy(Encuesta $encuesta)
    {
        $encuesta->delete();
        return redirect()->route('encuestas.index')->with('success', 'Encuesta eliminada exitosamente.');
    }
}
