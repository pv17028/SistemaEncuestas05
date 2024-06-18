<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResultadoEncuestaController extends Controller
{
    public function index()
    {
        $userId = auth()->id(); // Obtén el ID del usuario actual
    
        $encuestas = Encuesta::where('idUsuario', $userId) // Filtra las encuestas por el ID del usuario
            ->withCount('encuesta_usuario as respuestas_agrupadas')
            ->orderBy('titulo', 'asc')
            ->get();
    
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

        // Obtiene el número de respuestas (completas o no) por fecha
        $respuestasPorFecha = DB::table('encuesta_usuario')
            ->where('encuesta_id', $idEncuesta)
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('count(*) as count'))
            ->groupBy('fecha')
            ->get()
            ->keyBy('fecha')
            ->transform(function ($item) {
                return $item->count;
            })
            ->toArray();
        
        // Obtiene el número de respuestas completas e incompletas por fecha
        $respuestasCompletasPorFecha = DB::table('encuesta_usuario')
            ->where('encuesta_id', $idEncuesta)
            ->where('completa', true)
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('count(*) as count'))
            ->groupBy('fecha')
            ->get()
            ->keyBy('fecha')
            ->transform(function ($item) {
                return $item->count;
            })
            ->toArray();
        
        $respuestasIncompletasPorFecha = DB::table('encuesta_usuario')
            ->where('encuesta_id', $idEncuesta)
            ->where('completa', false)
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('count(*) as count'))
            ->groupBy('fecha')
            ->get()
            ->keyBy('fecha')
            ->transform(function ($item) {
                return $item->count;
            })
            ->toArray();
        
        // Retorna la vista de resultados de la encuesta con los datos de la encuesta y el número de respuestas por fecha
        return view('resultadoEncuesta.show', [
            'encuesta' => $encuesta, 
            'respuestasPorFecha' => $respuestasPorFecha,
            'respuestasCompletasPorFecha' => $respuestasCompletasPorFecha,
            'respuestasIncompletasPorFecha' => $respuestasIncompletasPorFecha
        ]);
    }
}