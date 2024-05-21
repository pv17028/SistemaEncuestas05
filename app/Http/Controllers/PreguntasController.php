<?php

namespace App\Http\Controllers;

use App\Models\preguntas;
use Illuminate\Http\Request;
use App\Models\TipoPregunta;

class PreguntasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $preguntas = preguntas::all();
        return view('preguntas.index', compact('preguntas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposPreguntas = TipoPregunta::all();
        return view('preguntas.create', compact('tiposPreguntas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idTipoPregunta' => 'required|integer',
            'contenidoPregunta' => 'required|string|max:256',
            'descripcionPregunta' => 'required|string|max:256',
            'criterioValidacion' => 'required|string|max:256',
            'posicionPregunta' => 'integer',
        ]);

        //verificar si la pregunta ya existe
        $pregunta = preguntas::where('contenidoPregunta', $request->input('contenidoPregunta'))
            ->first();

        if ($pregunta) {
            return redirect()->route('preguntas.index')
                ->with('error', 'La pregunta ya existe.');
        }

        $pregunta = new preguntas();
        $pregunta->idTipoPregunta = $request->input('idTipoPregunta');
        $pregunta->contenidoPregunta = $request->input('contenidoPregunta');
        $pregunta->descripcionPregunta = $request->input('descripcionPregunta');
        $pregunta->criterioValidacion = $request->input('criterioValidacion');
        $pregunta->posicionPregunta = 1;
        $pregunta->save();

        return redirect()->route('preguntas.index')
            ->with('success', 'Pregunta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(preguntas $preguntas)
    {
        return view('preguntas.show', compact('preguntas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(preguntas $preguntas)
    {
        $tiposPreguntas = TipoPregunta::all();
        return view('preguntas.edit', compact('preguntas', 'tiposPreguntas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, preguntas $preguntas)
    {
        $request->validate([
            'idTipoPregunta' => 'required|integer',
            'contenidoPregunta' => 'required|string|max:256',
            'descripcionPregunta' => 'required|string|max:256',
            'criterioValidacion' => 'required|string|max:256',
            'posicionPregunta' => 'integer',
        ]);

        
        $preguntas->idTipoPregunta = $request->input('idTipoPregunta');
        $preguntas->contenidoPregunta = $request->input('contenidoPregunta');
        $preguntas->descripcionPregunta = $request->input('descripcionPregunta');
        $preguntas->criterioValidacion = $request->input('criterioValidacion');
        $preguntas->save();

        return redirect()->route('preguntas.index')
            ->with('success', 'Pregunta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(preguntas $preguntas)
    {
        $preguntas->delete();
        return redirect()->route('preguntas.index')
            ->with('success', 'Pregunta eliminada exitosamente.');
    }
}
