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
        return view('gestionEncuestas.index', compact('encuestas', 'tiposPreguntas', 'cantidadTiposPreguntas'));
    }
}