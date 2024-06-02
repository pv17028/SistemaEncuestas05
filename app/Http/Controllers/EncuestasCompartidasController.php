<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use App\Models\Respuesta;
use Carbon\Carbon;

class EncuestasCompartidasController extends Controller
{

    public function index()
    {
        $userId = auth()->user()->id; // Obtiene el ID del usuario actual
        $now = Carbon::now(); // Obtiene la fecha y hora actual

        // Obtiene las encuestas compartidas con el usuario actual que no han vencido
        $encuestasCompartidas = Encuesta::where('compartida', true)
            ->where('fechaVencimiento', '>', $now)
            ->whereRaw("? = ANY(string_to_array(compartida_con, ','))", [$userId])
            ->orderBy('titulo')
            ->get();

        return view('ecompartidas.index', ['encuestasCompartidas' => $encuestasCompartidas]);
    }

    public function show($idEncuesta)
    {
        $userId = auth()->user()->id; // Obtiene el ID del usuario actual
        $now = Carbon::now(); // Obtiene la fecha y hora actual

        $encuestaCompartida = Encuesta::with('preguntas.opciones')
            ->where('idEncuesta', $idEncuesta)
            ->where('fechaVencimiento', '>', $now)
            ->first();

        // Si la encuesta no existe o ha vencido, redirige con un mensaje de error
        if (!$encuestaCompartida) {
            return redirect()->route('ecompartidas.index')->with('error', 'No tienes acceso a esta encuesta.');
        }

        // Verifica si la encuesta fue compartida con el usuario actual
        $compartidaCon = explode(',', $encuestaCompartida->compartida_con);
        if (!in_array($userId, $compartidaCon)) {
            // Si la encuesta no fue compartida con el usuario actual, redirige con un mensaje de error
            return redirect()->route('ecompartidas.index')->with('error', 'No tienes acceso a esta encuesta.');
        }

        return view('ecompartidas.show', ['encuestaCompartida' => $encuestaCompartida]);
    }

    public function store(Request $request, $idEncuesta)
    {
        $usuarioId = auth()->user()->id; // Obtén el ID del usuario actualmente autenticado

        foreach ($request->respuestas as $idPregunta => $idOpcion) {
            Respuesta::create([
                'encuesta_id' => $idEncuesta,
                'pregunta_id' => $idPregunta,
                'opcion_id' => $idOpcion,
                'usuario_id' => $usuarioId,
                // Si tienes una respuesta abierta, puedes guardarla aquí
                // 'respuesta_abierta' => $request->respuesta_abierta[$idPregunta],
            ]);
        }

        return redirect()->route('ecompartidas.index')->with('success', 'Tus respuestas han sido enviadas con éxito.');
    }
}