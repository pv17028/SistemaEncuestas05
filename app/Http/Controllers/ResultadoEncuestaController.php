<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use Carbon\Carbon;

class ResultadoEncuestaController extends Controller
{
    public function index()
    {
        $encuestas = Encuesta::orderBy('titulo', 'asc')->get();
    
        foreach ($encuestas as $encuesta) {
            $encuesta->respuestas_agrupadas = $encuesta->encuesta_usuario()
                ->selectRaw("DATE_TRUNC('second', created_at) as fecha_hora")
                ->groupBy('fecha_hora')
                ->get()
                ->count();
        }
    
        return view('resultadoEncuesta.index', ['encuestas' => $encuestas]);
    }

    public function show($idEncuesta)
    {
        // Obtiene la encuesta con sus preguntas y opciones
        $encuesta = Encuesta::with(['preguntas.opciones'])
            ->where('idEncuesta', $idEncuesta)
            ->first();

        // Verifica si la encuesta existe
        if (!$encuesta) {
            return redirect()->back()->with('error', 'Encuesta no encontrada');
        }

        // Verifica si el usuario actual es el propietario de la encuesta
        if (auth()->user()->id != $encuesta->idUsuario) {
            return redirect()->back()->with('error', 'No tienes permiso para ver esta encuesta');
        }

        // Para cada pregunta, obtiene el número de veces que cada opción fue seleccionada
        foreach ($encuesta->preguntas as $pregunta) {
            $pregunta->opciones = $pregunta->opciones()
                ->withCount('respuestas as seleccion_count')
                ->get();
        }

        // Retorna la vista de resultados de la encuesta con los datos de la encuesta
        return view('resultadoEncuesta.show', ['encuesta' => $encuesta]);
    }
}