<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use App\Models\TipoPregunta;

class GestionEncuestasController extends Controller
{
    public function index()
    {
        $encuestas = Encuesta::all();
        $tiposPreguntas = TipoPregunta::all();
        $cantidadTiposPreguntas = $tiposPreguntas->count();
        $cantidadTiposPreguntasHabilitadas = TipoPregunta::where('habilitado', 1)->count();
        $cantidadTiposPreguntasDeshabilitadas = TipoPregunta::where('habilitado', 0)->count();
        return view('gestionEncuestas.index', compact('encuestas', 'tiposPreguntas', 'cantidadTiposPreguntas', 'cantidadTiposPreguntasHabilitadas', 'cantidadTiposPreguntasDeshabilitadas'));
    }
}
