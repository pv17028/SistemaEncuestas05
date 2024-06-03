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

        $encuestaCompartida = Encuesta::with('preguntas.opciones', 'preguntas.tipoPregunta')
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

        // Obtiene las preguntas de la encuesta
        $preguntas = $encuestaCompartida->preguntas;

        return view('ecompartidas.show', ['encuestaCompartida' => $encuestaCompartida, 'preguntas' => $preguntas]);
    }

    public function store(Request $request, $idEncuesta)
    {
        $usuarioId = auth()->user()->id; // Obtén el ID del usuario actualmente autenticado
        $respuestaIds = []; // Almacena los IDs de las respuestas creadas

        foreach ($request->respuestas as $idPregunta => $respuestas) {
            // Si la pregunta permite múltiples respuestas, $respuestas será un array
            // En ese caso, necesitamos crear una respuesta para cada opción seleccionada
            if (is_array($respuestas)) {
                foreach ($respuestas as $respuesta) {
                    if (is_numeric($respuesta)) {
                        // Si la respuesta es numérica, es un ID de opción
                        $respuestaCreada = Respuesta::create([
                            'encuesta_id' => $idEncuesta,
                            'pregunta_id' => $idPregunta,
                            'opcion_id' => $respuesta,
                            'usuario_id' => $usuarioId,
                        ]);
                        $respuestaIds[] = $respuestaCreada->id; // Guarda el ID de la respuesta
                    } else {
                        // Si la respuesta no es numérica, es una respuesta escrita por el usuario
                        $respuestaCreada = Respuesta::create([
                            'encuesta_id' => $idEncuesta,
                            'pregunta_id' => $idPregunta,
                            'respuesta_abierta' => $respuesta,
                            'usuario_id' => $usuarioId,
                        ]);
                        $respuestaIds[] = $respuestaCreada->id; // Guarda el ID de la respuesta
                    }
                }
            } else {
                // Si la pregunta solo permite una respuesta, $respuestas será el ID de la opción seleccionada
                // o una respuesta escrita por el usuario
                if (is_numeric($respuestas)) {
                    // Si la respuesta es numérica, es un ID de opción
                    $respuestaCreada = Respuesta::create([
                        'encuesta_id' => $idEncuesta,
                        'pregunta_id' => $idPregunta,
                        'opcion_id' => $respuestas,
                        'usuario_id' => $usuarioId,
                    ]);
                    $respuestaIds[] = $respuestaCreada->id; // Guarda el ID de la respuesta
                } else {
                    // Si la respuesta no es numérica, es una respuesta escrita por el usuario
                    $respuestaCreada = Respuesta::create([
                        'encuesta_id' => $idEncuesta,
                        'pregunta_id' => $idPregunta,
                        'respuesta_abierta' => $respuestas,
                        'usuario_id' => $usuarioId,
                    ]);
                    $respuestaIds[] = $respuestaCreada->id; // Guarda el ID de la respuesta
                }
            }
        }

        // Aquí manejamos las respuestas de texto abiertas
        if ($request->has('otra')) {
            foreach ($request->otra as $idPregunta => $respuesta) {
                if (!empty($respuesta)) {
                    // Busca la respuesta con la opción "Otra" seleccionada y actualiza la respuesta abierta
                    $respuestaOtra = Respuesta::whereIn('id', $respuestaIds) // Solo busca entre las respuestas creadas en esta solicitud
                        ->where('encuesta_id', $idEncuesta)
                        ->where('pregunta_id', $idPregunta)
                        ->where('opcion_id', function($query) {
                            $query->select('idOpcion')
                                ->from('opcions')
                                ->where('contenidoOpcion', 'Otra')
                                ->limit(1);
                        })
                        ->first();

                    if ($respuestaOtra) {
                        $respuestaOtra->respuesta_abierta = $respuesta;
                        $respuestaOtra->save();
                    }
                }
            }
        }

        return redirect()->route('ecompartidas.index')->with('success', 'Tus respuestas han sido enviadas con éxito.');
    }
}