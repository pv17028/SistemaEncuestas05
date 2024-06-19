<?php

namespace App\Http\Controllers;

use App\Models\Opcion;
use Illuminate\Http\Request;
use App\Models\preguntas;

class OpcionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, preguntas $preguntas)
    {
        $request->validate([
            'contenidoOpcion' => 'required|string|max:256',
            'posicionOpcion' => 'integer',
        ]);

        //verificar si la opcion ya existe para la pregunta
        $opcion = Opcion::where('idPregunta', $preguntas->idPregunta)
            ->where('contenidoOpcion', $request->input('contenidoOpcion'))
            ->first();

        if ($opcion) {
            return back()->with('error', 'La opción ya existe para la pregunta.');
        }

        $opcion = new Opcion();
        $opcion->idPregunta = $preguntas->idPregunta;
        $opcion->contenidoOpcion = $request->input('contenidoOpcion');
        $opcion->posicionOpcion = 1;
        $opcion->save();

        return back()->with('success', 'Opción creada exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, preguntas $preguntas, Opcion $opcion )
    {
        $request->validate([
            'contenidoOpcion' => 'required|string|max:256',
            'posicionOpcion' => 'integer',
        ]);

        $opcion->contenidoOpcion = $request->input('contenidoOpcion');
        $opcion->posicionOpcion = 1;
        $opcion->save();
        return back()->with('success', 'Opción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(preguntas $preguntas, Opcion $opcion )
    {
        $opcion->delete();
        return back()->with('success', 'Opción eliminada exitosamente.');
    }
}