<?php

namespace App\Http\Controllers;

use App\Models\opcion;
use Illuminate\Http\Request;
use App\Models\preguntas;

class OpcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(preguntas $preguntas)
    {
        $opciones = $preguntas->opciones;
        return view('opciones.index', compact('opciones', 'preguntas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(preguntas $preguntas)
    {
        return view('opciones.create', compact('preguntas'));
    }

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
        $opcion = opcion::where('idPregunta', $preguntas->idPregunta)
            ->where('contenidoOpcion', $request->input('contenidoOpcion'))
            ->first();

        if ($opcion) {
            return redirect()->route('preguntas.opciones.index', $preguntas->idPregunta)
                ->with('error', 'La opción ya existe para la pregunta.');
        }

        $opcion = new opcion();
        $opcion->idPregunta = $preguntas->idPregunta;
        $opcion->contenidoOpcion = $request->input('contenidoOpcion');
        $opcion->posicionOpcion = 1;
        $opcion->save();

        return redirect()->route('preguntas.opciones.index', $preguntas->idPregunta)
            ->with('success', 'Opción creada exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(opcion $opcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(preguntas $preguntas, opcion $opcion)
    {
        return view('opciones.edit', compact('preguntas', 'opcion'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,preguntas $preguntas, opcion $opcion )
    {
        $request->validate([
            'contenidoOpcion' => 'required|string|max:256',
            'posicionOpcion' => 'integer',
        ]);

        $opcion->contenidoOpcion = $request->input('contenidoOpcion');
        $opcion->posicionOpcion = 1;
        $opcion->save();
        return redirect()->route('preguntas.opciones.index', $preguntas->idPregunta)
        ->with('success', 'Opción actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(preguntas $preguntas, opcion $opcion )
    {
        $opcion->delete();
        return redirect()->route('preguntas.opciones.index', $preguntas->idPregunta)
            ->with('success', 'Opción eliminada exitosamente.');
    }
    
}
