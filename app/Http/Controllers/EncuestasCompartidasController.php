<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use App\Models\Respuesta;
use App\Models\Opcion;
use App\Models\EncuestaUsuario;
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
            ->with(['encuesta_usuario' => function ($query) use ($userId) {
                $query->where('usuario_id', $userId)
                    ->select('encuesta_id', 'completa', 'preguntas_no_respondidas');
            }])
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
        $encuesta = Encuesta::find($idEncuesta);
        $usuarioId = $encuesta->es_anonima ? null : auth()->user()->id;
        $respuestaIds = [];
        $preguntasNoRespondidasIds = [];

        $preguntaIds = $encuesta->preguntas->pluck('idPregunta')->toArray();

        foreach ($preguntaIds as $idPregunta) {
            $respuestas = $request->respuestas[$idPregunta] ?? null;
            $respuestaCreada = $this->crearRespuesta($idEncuesta, $idPregunta, $respuestas, $usuarioId);

            if (!empty($respuestas)) {
                if (is_array($respuestaCreada)) {
                    foreach ($respuestaCreada as $respuesta) {
                        $respuestaIds[] = $respuesta->id;
                    }
                } else {
                    $respuestaIds[] = $respuestaCreada->id;
                }
            } else {
                if (is_array($respuestaCreada)) {
                    foreach ($respuestaCreada as $respuesta) {
                        $preguntasNoRespondidasIds[] = $respuesta->id;
                    }
                } else {
                    $preguntasNoRespondidasIds[] = $respuestaCreada->id;
                }
            }
        }

        if ($request->has('otra')) {
            foreach ($request->otra as $idPregunta => $respuesta) {
                if (!empty($respuesta)) {
                    $respuestaOtra = Respuesta::whereIn('id', $respuestaIds)
                        ->where('encuesta_id', $idEncuesta)
                        ->where('pregunta_id', $idPregunta)
                        ->where('opcion_id', function ($query) {
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

        $preguntasRespondidas = count($respuestaIds);
        $preguntasTotales = $encuesta->preguntas()->count();
        $completa = count($preguntasNoRespondidasIds) == 0;

        EncuestaUsuario::create([
            'encuesta_id' => $idEncuesta,
            'usuario_id' => $encuesta->es_anonima ? null : $usuarioId,
            'respuesta_ids' => json_encode($respuestaIds),
            'preguntas_no_respondidas' => json_encode($preguntasNoRespondidasIds),
            'completa' => $completa,
        ]);

        return redirect()->route('ecompartidas.index')->with('success', 'Tus respuestas han sido enviadas con éxito.');
    }

    private function crearRespuesta($idEncuesta, $idPregunta, $respuesta, $usuarioId)
    {
        $datos = [
            'encuesta_id' => $idEncuesta,
            'pregunta_id' => $idPregunta,
            'usuario_id' => $usuarioId,
        ];

        if (is_array($respuesta)) {
            $respuestasCreadas = [];
            foreach ($respuesta as $resp) {
                if (is_numeric($resp) && Opcion::find($resp)) {
                    $datos['opcion_id'] = $resp;
                    $datos['respuesta_abierta'] = null;
                } else {
                    $datos['respuesta_abierta'] = $resp;
                    $datos['opcion_id'] = null;
                }
                $respuestasCreadas[] = Respuesta::create($datos);
            }
            return $respuestasCreadas;
        } else {
            if (is_numeric($respuesta) && Opcion::find($respuesta)) {
                $datos['opcion_id'] = $respuesta;
            } else {
                $datos['respuesta_abierta'] = $respuesta;
            }
            return Respuesta::create($datos);
        }
    }

    public function edit($idEncuesta)
    {
        $userId = auth()->user()->id; // Obtiene el ID del usuario actual
        $now = Carbon::now(); // Obtiene la fecha y hora actual

        $encuestaCompartida = Encuesta::with(['preguntas.opciones', 'preguntas.tipoPregunta', 'usuario', 'respuestas' => function ($query) use ($userId) {
            $query->where('usuario_id', $userId);
        }])
            ->where('idEncuesta', $idEncuesta)
            ->where('fechaVencimiento', '>', $now)
            ->first();

        // Si la encuesta no existe o ha vencido, redirige con un mensaje de error
        if (!$encuestaCompartida) {
            return redirect()->route('ecompartidas.index')->with('error', 'No tienes acceso a esta encuesta.');
        }

        // Verifica si la encuesta fue compartida con el usuario actual
        $compartidaCon = $encuestaCompartida->usuario->pluck('id')->toArray();
        if (!in_array($userId, $compartidaCon)) {
            // Si la encuesta no fue compartida con el usuario actual, redirige con un mensaje de error
            return redirect()->route('ecompartidas.index')->with('error', 'No tienes acceso a esta encuesta.');
        }

        // Obtiene las preguntas y las respuestas de la encuesta
        $preguntas = $encuestaCompartida->preguntas;
        $respuestas = $encuestaCompartida->respuestas;

        $respuestasPorPregunta = [];
        foreach ($respuestas as $respuesta) {
            if (!empty($respuesta->opcion_id)) {
                $respuestasPorPregunta[$respuesta->pregunta_id]['opcion_id'][] = $respuesta->opcion_id;
            }
            if (!empty($respuesta->respuesta_abierta)) {
                $respuestasPorPregunta[$respuesta->pregunta_id]['respuesta_abierta'] = $respuesta->respuesta_abierta;
            }
        }

        return view('ecompartidas.edit', ['encuestaCompartida' => $encuestaCompartida, 'preguntas' => $preguntas, 'respuestas' => $respuestasPorPregunta]);
    }

    public function update(Request $request, $idEncuesta)
    {
        $encuesta = Encuesta::find($idEncuesta);

        // Obtiene el ID del usuario actual
        $userId = auth()->user()->id;

        // Verifica si hay registros existentes para el usuario
        $existenRegistros = EncuestaUsuario::where('encuesta_id', $idEncuesta)
            ->where('usuario_id', $userId)
            ->exists();

        // Busca la encuesta
        $encuestaCompartida = Encuesta::find($idEncuesta);

        // Verifica si la encuesta existe y si el usuario tiene permiso para editarla
        if (!$encuestaCompartida || $encuestaCompartida->usuario->id != $userId) {
            return redirect()->route('ecompartidas.index')->with('error', 'No tienes acceso a esta encuesta.');
        }

        // Actualiza los datos de la encuesta
        $encuestaCompartida->update($request->all());

        // Actualiza las respuestas
        foreach ($request->respuestas as $preguntaId => $respuesta) {
            // Si la respuesta es un array, es una respuesta mixta o una pregunta con múltiples opciones
            if (is_array($respuesta)) {
                // Si la respuesta es mixta
                if (isset($respuesta['respuesta_abierta']) && !empty($respuesta['respuesta_abierta'])) {
                    $respuestaAbierta = $respuesta['respuesta_abierta'];
                    $opcionId = $respuesta['opcion_id'] ?? null;

                    // Verifica si la opción es 'Otra'
                    $opcion = Opcion::find($opcionId);
                    if ($opcion && $opcion->contenidoOpcion == 'Otra') {
                        // Esta es una respuesta mixta con la opción 'Otra'
                        // Guarda la respuesta abierta
                        $respuesta = $encuestaCompartida->respuestas()->where('pregunta_id', $preguntaId)->first();
                        if ($respuesta) {
                            $respuesta->update(['opcion_id' => $opcionId, 'respuesta_abierta' => $respuestaAbierta]);
                        } else {
                            // Si la encuesta es anónima y no hay registros existentes para el usuario, establece userId en null
                            if ($encuestaCompartida->es_anonima && !$existenRegistros) {
                                $userId = null;
                            }
                            // Crea una nueva respuesta
                            $encuestaCompartida->respuestas()->create([
                                'pregunta_id' => $preguntaId,
                                'opcion_id' => $opcionId,
                                'respuesta_abierta' => $respuestaAbierta,
                                'usuario_id' => $userId
                            ]);
                        }
                    }
                } else {
                    // Es una pregunta con múltiples opciones
                    // Obtiene todas las respuestas existentes para esta pregunta
                    $respuestasExistentes = $encuestaCompartida->respuestas()->where('pregunta_id', $preguntaId)->get();

                    // Crea un array con los IDs de las opciones seleccionadas
                    $opcionesSeleccionadas = [];
                    foreach ($respuesta as $id) {
                        $respuesta = $respuestasExistentes->where('opcion_id', $id)->first();
                        if ($respuesta) {
                            $respuesta->update(['opcion_id' => $id]);
                        } else {
                            // Si la encuesta es anónima y no hay registros existentes para el usuario, establece userId en null
                            if ($encuestaCompartida->es_anonima && !$existenRegistros) {
                                $userId = null;
                            }

                            // Crea una nueva respuesta
                            $respuesta = $encuestaCompartida->respuestas()->create([
                                'pregunta_id' => $preguntaId,
                                'opcion_id' => $id,
                                'usuario_id' => $userId
                            ]);
                        }
                        $opcionesSeleccionadas[] = $respuesta->id;
                    }

                    // Elimina las respuestas que ya no están seleccionadas
                    foreach ($respuestasExistentes as $respuesta) {
                        if (!in_array($respuesta->id, $opcionesSeleccionadas)) {
                            $respuesta->delete();
                        }
                    }
                }
            } else {
                // Si la respuesta no es un array, puede ser una pregunta abierta o una pregunta de opción única
                if (!empty($respuesta)) {
                    $respuestaAbierta = null;
                    $opcionId = null;

                    // Verifica si la respuesta es numérica (ID de opción) o una cadena (respuesta abierta)
                    if (is_numeric($respuesta)) {
                        $opcionId = $respuesta;
                    } else {
                        $respuestaAbierta = $respuesta;
                    }

                    $respuesta = $encuestaCompartida->respuestas()->where('pregunta_id', $preguntaId)->first();
                    if ($respuesta) {
                        $respuesta->update(['respuesta_abierta' => $respuestaAbierta, 'opcion_id' => $opcionId]);
                    } else {
                        // Si la encuesta es anónima y no hay registros existentes para el usuario, establece userId en null
                        if ($encuestaCompartida->es_anonima && !$existenRegistros) {
                            $userId = null;
                        }

                        // Crea una nueva respuesta
                        $encuestaCompartida->respuestas()->create([
                            'pregunta_id' => $preguntaId,
                            'respuesta_abierta' => $respuestaAbierta,
                            'opcion_id' => $opcionId,
                            'usuario_id' => $userId
                        ]);
                    }
                }
            }
        }

        if ($userId !== null) {
            // Busca la entrada correspondiente en la tabla EncuestaUsuario
            $encuestaUsuario = EncuestaUsuario::where('encuesta_id', $idEncuesta)
                ->where('usuario_id', $userId)
                ->first();

            $respuestaIds = [];
            $preguntasNoRespondidasIds = [];
            $preguntasRespondidasIds = [];

            // Obtén todas las respuestas de la encuesta compartida
            $respuestas = $encuestaCompartida->respuestas;

            foreach ($respuestas as $respuesta) {
                $respuestaIds[] = $respuesta->id;

                // Si ambas son nulas, la pregunta no fue respondida
                if (is_null($respuesta->opcion_id) && is_null($respuesta->respuesta_abierta)) {
                    $preguntasNoRespondidasIds[] = $respuesta->id; // Cambiado a ID de respuesta
                }
                // Si solo respuesta_abierta es nula, podría ser una pregunta de opción múltiple no respondida
                else if (is_null($respuesta->respuesta_abierta)) {
                    // Verifica si la pregunta es de opción múltiple
                    if (
                        $respuesta->pregunta->tipoPregunta->nombreTipoPregunta == 'Preguntas de elección múltiple' ||
                        $respuesta->pregunta->tipoPregunta->nombreTipoPregunta == 'Preguntas dicotómicas' ||
                        $respuesta->pregunta->tipoPregunta->nombreTipoPregunta == 'Preguntas politómicas' ||
                        $respuesta->pregunta->tipoPregunta->nombreTipoPregunta == 'Preguntas de tipo ranking' ||
                        $respuesta->pregunta->tipoPregunta->nombreTipoPregunta == 'Escala de Likert' ||
                        $respuesta->pregunta->tipoPregunta->nombreTipoPregunta == 'Escala numérica' ||
                        $respuesta->pregunta->tipoPregunta->nombreTipoPregunta == 'Preguntas mixtas'
                    ) {
                        $preguntasRespondidasIds[] = $respuesta->id; // Cambiado a ID de respuesta
                    }
                }
                // Si solo opcion_id es nula, podría ser una pregunta abierta no respondida
                else if (is_null($respuesta->opcion_id)) {
                    // Verifica si la pregunta es abierta
                    if ($respuesta->pregunta->tipoPregunta->nombreTipoPregunta == 'Preguntas abiertas') {
                        $preguntasRespondidasIds[] = $respuesta->id; // Cambiado a ID de respuesta
                    }
                }
            }

            // Calcula si la encuesta está completa o no
            $preguntasNoRespondidas = count($preguntasNoRespondidasIds);
            $preguntasTotales = $encuesta->preguntas()->count();

            if (empty($preguntasNoRespondidasIds)) {
                $completa = true;
            } else {
                $completa = intval($preguntasNoRespondidas) >= intval($preguntasTotales);
            }

            // Si la entrada existe, actualízala
            if ($encuestaUsuario) {
                $encuestaUsuario->update([
                    'respuesta_ids' => json_encode($respuestaIds),
                    'preguntas_no_respondidas' => json_encode($preguntasNoRespondidasIds),
                    'completa' => $completa,
                ]);
            } else {
                // Si la entrada no existe, créala
                EncuestaUsuario::create([
                    'encuesta_id' => $idEncuesta,
                    'usuario_id' => $userId,
                    'respuesta_ids' => json_encode($respuestaIds),
                    'preguntas_no_respondidas' => json_encode($preguntasNoRespondidasIds),
                    'completa' => $completa,
                ]);
            }
        }

        return redirect()->route('ecompartidas.index')->with('success', 'Encuesta actualizada con éxito.');
    }
}
