<?php

namespace App\Http\Controllers;

use App\Models\preguntas;
use Illuminate\Http\Request;
use App\Models\TipoPregunta;
use App\Models\Encuesta;
use App\Models\Opcion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PreguntasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idEncuesta)
    {
        $encuesta = Encuesta::find($idEncuesta);
        $preguntas = Preguntas::where('idEncuesta', $idEncuesta)
            ->orderBy('contenidoPregunta', 'asc') // Ordena las preguntas por 'contenidoPregunta' en orden ascendente
            ->get();
        return view('preguntas.index', ['preguntas' => $preguntas, 'encuesta' => $encuesta, 'idEncuesta' => $idEncuesta]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idEncuesta)
    {
        $tiposPreguntas = TipoPregunta::all();
        return view('preguntas.create', compact('tiposPreguntas', 'idEncuesta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idEncuesta' => 'required|integer',
            'idTipoPregunta' => 'required|string|max:256',
            'contenidoPregunta' => 'required|string|max:256',
            'descripcionPregunta' => 'required|string|max:256',
            'criterioValidacion' => 'nullable|string|max:256',
            'posicionPregunta' => 'integer',
        ]);

        $pregunta = preguntas::where('idEncuesta', $request->input('idEncuesta'))
            ->where('contenidoPregunta', $request->input('contenidoPregunta'))
            ->first();

        if ($pregunta) {
            return redirect()->route('preguntas.create', ['idEncuesta' => $request->idEncuesta])
                ->with('error', 'La pregunta ya existe en esta encuesta.');
        }

        // Obtener el ID del tipo de pregunta basado en su nombre
        $tipoPregunta = TipoPregunta::where('nombreTipoPregunta', $request->input('idTipoPregunta'))->first();

        if (!$tipoPregunta) {
            return redirect()->route('preguntas.create', ['idEncuesta' => $request->idEncuesta])
                ->with('error', 'Tipo de pregunta no válido.');
        }

        $pregunta = new preguntas();
        $pregunta->idEncuesta = $request->input('idEncuesta');
        if ($tipoPregunta) {
            $pregunta->idTipoPregunta = $tipoPregunta->idTipoPregunta;
        }
        $pregunta->contenidoPregunta = $request->input('contenidoPregunta');
        $pregunta->descripcionPregunta = $request->input('descripcionPregunta');
        $pregunta->criterioValidacion = $request->input('criterioValidacion');
        $pregunta->posicionPregunta = 1;

        // Valida que solo se proporcionen dos opciones para "Preguntas dicotómicas"
        if ($request->input('idTipoPregunta') == 'Preguntas dicotómicas') {
            $opciones = $request->input('opcionesDicotomicas');
            if (count($opciones) != 2) {
                return back()->withErrors(['opcionesDicotomicas' => 'Debe proporcionar exactamente dos opciones para las preguntas dicotómicas.']);
            }
        }

        // Valida que solo se proporcionen 4 o 5 opciones para "Preguntas de tipo ranking"
        if ($request->input('idTipoPregunta') == 'Preguntas de tipo ranking') {
            $opciones = explode(',', $request->input('opcionesRanking'));
            if (count($opciones) < 4 || count($opciones) > 5) {
                return back()->withErrors(['opcionesRanking' => 'Debe proporcionar 4 o 5 opciones para las preguntas de tipo ranking.']);
            }
        }

        // Valida que el rango máximo sea válido para "Escala numérica"
        if ($request->input('idTipoPregunta') == 'Escala numérica') {
            $rangoMaximo = $request->input('escalaNumerica');
            if (!is_numeric($rangoMaximo) || $rangoMaximo < 1) {
                return back()->withErrors(['escalaNumerica' => 'Debe proporcionar un rango máximo válido para la escala numérica.']);
            }
        }

        DB::beginTransaction(); // Inicia una nueva transacción

        try {
            $pregunta->save(); // Intenta guardar la pregunta

            // Si el tipo de pregunta es "Preguntas dicotómicas"
            if ($request->input('idTipoPregunta') == 'Preguntas dicotómicas') {
                $opciones = $request->input('opcionesDicotomicas'); // Obtiene las opciones proporcionadas por el usuario

                $posicion = 1; // Inicializa la posición de la opción

                // Para cada opción proporcionada por el usuario
                foreach ($opciones as $contenidoOpcion) {
                    // Crea una nueva opción en la base de datos
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                        'posicionOpcion' => $posicion, // Establece la posición de la opción
                    ]);
                    $posicion++; // Incrementa la posición para la siguiente opción
                }
            }

            // Si el tipo de pregunta es "Preguntas politómicas"
            if ($request->input('idTipoPregunta') == 'Preguntas politómicas') {
                $posicion = 1; // Inicializa la posición de la opción
                $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesPolitomicas'));
                $opciones = array_map(function($opcion) {
                    return trim(str_replace(['(', ')'], '', $opcion));
                }, $opciones);

                // Para cada opción proporcionada por el usuario
                foreach ($opciones as $contenidoOpcion) {
                    // Crea una nueva opción en la base de datos
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                        'posicionOpcion' => $posicion, // Establece la posición de la opción
                    ]);
                    $posicion++; // Incrementa la posición para la siguiente opción
                }
            }

            // Si el tipo de pregunta es "Preguntas de elección múltiple"
            if ($request->input('idTipoPregunta') == 'Preguntas de elección múltiple') {
                $posicion = 1; // Inicializa la posición de la opción
                $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesMultiple'));
                $opciones = array_map(function($opcion) {
                    return trim(str_replace(['(', ')'], '', $opcion));
                }, $opciones);

                // Para cada opción proporcionada por el usuario
                foreach ($opciones as $contenidoOpcion) {
                    // Crea una nueva opción en la base de datos
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                        'posicionOpcion' => $posicion, // Establece la posición de la opción
                    ]);
                    $posicion++; // Incrementa la posición para la siguiente opción
                }
            }

            // Si el tipo de pregunta es "Preguntas de tipo ranking"
            if ($request->input('idTipoPregunta') == 'Preguntas de tipo ranking') {
                $posicion = 1; // Inicializa la posición de la opción
                $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesRanking'));
                $opciones = array_map(function($opcion) {
                    return trim(str_replace(['(', ')'], '', $opcion));
                }, $opciones);

                // Para cada opción proporcionada por el usuario
                foreach ($opciones as $contenidoOpcion) {
                    // Crea una nueva opción en la base de datos
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                        'posicionOpcion' => $posicion, // Establece la posición de la opción
                    ]);
                    $posicion++; // Incrementa la posición para la siguiente opción
                }
            }

            // Si el tipo de pregunta es "Escala de Likert"
            if ($request->input('idTipoPregunta') == 'Escala de Likert') {
                $posicion = 1; // Inicializa la posición de la opción
                $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesLikert'));
                $opciones = array_map(function($opcion) {
                    return trim(str_replace(['(', ')'], '', $opcion));
                }, $opciones);

                // Para cada opción proporcionada por el usuario
                foreach ($opciones as $contenidoOpcion) {
                    // Crea una nueva opción en la base de datos
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                        'posicionOpcion' => $posicion, // Establece la posición de la opción
                    ]);
                    $posicion++; // Incrementa la posición para la siguiente opción
                }
            }

            // Si el tipo de pregunta es "Escala numérica"
            if ($request->input('idTipoPregunta') == 'Escala numérica') {
                $rangoMaximo = $request->input('escalaNumerica'); // Obtiene el rango máximo proporcionado por el usuario

                // Para cada número en el rango
                for ($i = 1; $i <= $rangoMaximo; $i++) {
                    // Crea una nueva opción en la base de datos
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => $i, // Establece el contenido de la opción
                        'posicionOpcion' => $i, // Establece la posición de la opción
                    ]);
                }
            }

            // Si el tipo de pregunta es "Preguntas mixtas"
            if ($request->input('idTipoPregunta') == 'Preguntas mixtas') {
                $posicion = 1; // Inicializa la posición de la opción
                $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesMixtas'));
                $opciones = array_map(function($opcion) {
                    return trim(str_replace(['(', ')'], '', $opcion));
                }, $opciones);

                // Para cada opción proporcionada por el usuario
                foreach ($opciones as $contenidoOpcion) {
                    // Crea una nueva opción en la base de datos
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                        'posicionOpcion' => $posicion, // Establece la posición de la opción
                    ]);
                    $posicion++; // Incrementa la posición para la siguiente opción
                }

                // Verifica si la última opción ingresada por el usuario es "Otra"
                if (end($opciones) != 'Otra') {
                    // Si no es "Otra", crea una opción adicional llamada "Otra"
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => 'Otra', // Establece el contenido de la opción
                        'posicionOpcion' => $posicion, // Establece la posición de la opción
                    ]);
                }
            }

            DB::commit(); // Si todo salió bien, confirma la transacción
        } catch (\Exception $e) {
            DB::rollback(); // Si algo salió mal, revierte la transacción

            // Redirige al usuario de vuelta al formulario con un mensaje de error
            return back()->withErrors(['opciones' => 'Hubo un error al validar las opciones.']);
        }

        if ($request->input('save_and_close')) {
            return redirect()->route('preguntas.index', ['idEncuesta' => $request->idEncuesta])
                ->with('success', 'Pregunta agregada a la encuesta');
        } else {
            return redirect()->route('preguntas.create', ['idEncuesta' => $request->idEncuesta])
                ->with('success', 'Pregunta agregada a la encuesta');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($idEncuesta, $idPregunta)
    {
        $encuesta = Encuesta::find($idEncuesta);
        $preguntas = Preguntas::find($idPregunta);
        if (!$preguntas) {
            return redirect()->back()->with('error', 'Pregunta no encontrada.');
        }

        // Cargar las opciones para la pregunta
        $preguntas->load('opciones');

        return view('preguntas.show', compact('preguntas', 'encuesta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idEncuesta, $idPregunta)
    {
        $preguntas = preguntas::find($idPregunta);
        if (!$preguntas) {
            return redirect()->back()->with('error', 'Pregunta no encontrada.');
        }
    
        $encuesta = Encuesta::find($idEncuesta);
        if ($encuesta->compartida) {
            return redirect()->back()->with('error', 'La encuesta está compartida, por lo que no se puede editar.');
        }
    
        $tiposPreguntas = TipoPregunta::all();
        return view('preguntas.edit', compact('preguntas', 'tiposPreguntas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idEncuesta, $id)
    {
        $request->validate([
            'idEncuesta' => 'required|integer',
            'idTipoPregunta' => 'required|string|max:256',
            'contenidoPregunta' => 'required|string|max:256',
            'descripcionPregunta' => 'required|string|max:256',
            'criterioValidacion' => 'nullable|string|max:256',
            'posicionPregunta' => 'integer',
        ]);

        $preguntaExistente = Preguntas::where('idEncuesta', $request->input('idEncuesta'))
            ->where('contenidoPregunta', $request->input('contenidoPregunta'))
            ->where('idPregunta', '!=', $id)
            ->first();

        if ($preguntaExistente) {
            return redirect()->route('preguntas.edit', ['id' => $id, 'idEncuesta' => $request->idEncuesta])
                ->with('error', 'La pregunta ya existe en esta encuesta.');
        }

        $pregunta = Preguntas::find($id);

        if (!$pregunta) {
            return redirect()->route('preguntas.index', ['idEncuesta' => $request->idEncuesta])
                    ->with('error', 'La pregunta no existe.');
        }

        $tipoPregunta = TipoPregunta::where('nombreTipoPregunta', $request->input('idTipoPregunta'))->first();

        if (!$tipoPregunta) {
            return redirect()->route('preguntas.create', ['idEncuesta' => $request->idEncuesta])
                ->with('error', 'Tipo de pregunta no válido.');
        }

        $pregunta->idEncuesta = $request->input('idEncuesta');
        if ($tipoPregunta) {
            $pregunta->idTipoPregunta = $tipoPregunta->idTipoPregunta;
        }
        $pregunta->contenidoPregunta = $request->input('contenidoPregunta');
        $pregunta->descripcionPregunta = $request->input('descripcionPregunta');
        $pregunta->criterioValidacion = $request->input('criterioValidacion');
        $pregunta->posicionPregunta = 1;
        $pregunta->save();

        // Si el tipo de pregunta es "Preguntas dicotómicas"
        if ($request->input('idTipoPregunta') == 'Preguntas dicotómicas') {
            $opciones = $request->input('opcionesDicotomicas'); // Obtiene las opciones proporcionadas por el usuario

            // Valida que solo se proporcionen dos opciones
            if (count($opciones) != 2) {
                return back()->withErrors(['opcionesDicotomicas' => 'Debe proporcionar exactamente dos opciones para las preguntas dicotómicas.']);
            }

            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            $posicion = 1; // Inicializa la posición de la opción

            // Para cada opción proporcionada por el usuario
            foreach ($opciones as $contenidoOpcion) {
                // Crea una nueva opción en la base de datos
                Opcion::create([
                    'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                    'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                    'posicionOpcion' => $posicion, // Establece la posición de la opción
                ]);
                $posicion++; // Incrementa la posición para la siguiente opción
            }
        }

        // Si el tipo de pregunta es "Preguntas politómicas"
        if ($request->input('idTipoPregunta') == 'Preguntas politómicas') {
            $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesPolitomicas'));
            $opciones = array_map(function($opcion) {
                return trim(str_replace(['(', ')'], '', $opcion));
            }, $opciones);

            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            $posicion = 1; // Inicializa la posición de la opción

            // Para cada opción proporcionada por el usuario
            foreach ($opciones as $contenidoOpcion) {
                // Crea una nueva opción en la base de datos
                Opcion::create([
                    'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                    'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                    'posicionOpcion' => $posicion, // Establece la posición de la opción
                ]);
                $posicion++; // Incrementa la posición para la siguiente opción
            }
        }

        // Si el tipo de pregunta es "Preguntas de elección múltiple"
        if ($request->input('idTipoPregunta') == 'Preguntas de elección múltiple') {
            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            $posicion = 1; // Inicializa la posición de la opción
            $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesMultiple'));
            $opciones = array_map(function($opcion) {
                return trim(str_replace(['(', ')'], '', $opcion));
            }, $opciones);

            // Para cada opción proporcionada por el usuario
            foreach ($opciones as $contenidoOpcion) {
                // Crea una nueva opción en la base de datos
                Opcion::create([
                    'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                    'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                    'posicionOpcion' => $posicion, // Establece la posición de la opción
                ]);
                $posicion++; // Incrementa la posición para la siguiente opción
            }
        }

        // Si el tipo de pregunta es "Preguntas de tipo ranking"
        if ($request->input('idTipoPregunta') == 'Preguntas de tipo ranking') {
            $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesRanking'));
            $opciones = array_map(function($opcion) {
                return trim(str_replace(['(', ')'], '', $opcion));
            }, $opciones);

            // Valida que solo se proporcionen 4 o 5 opciones
            if (count($opciones) < 4 || count($opciones) > 5) {
                return back()->withErrors(['opcionesRanking' => 'Debe proporcionar 4 o 5 opciones para las preguntas de tipo ranking.']);
            }

            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            $posicion = 1; // Inicializa la posición de la opción

            // Para cada opción proporcionada por el usuario
            foreach ($opciones as $contenidoOpcion) {
                // Crea una nueva opción en la base de datos
                Opcion::create([
                    'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                    'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                    'posicionOpcion' => $posicion, // Establece la posición de la opción
                ]);
                $posicion++; // Incrementa la posición para la siguiente opción
            }
        }

        // Si el tipo de pregunta es "Escala de Likert"
        if ($request->input('idTipoPregunta') == 'Escala de Likert') {
            $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesLikert'));
            $opciones = array_map(function($opcion) {
                return trim(str_replace(['(', ')'], '', $opcion));
            }, $opciones);

            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            $posicion = 1; // Inicializa la posición de la opción

            // Para cada opción proporcionada por el usuario
            foreach ($opciones as $contenidoOpcion) {
                // Crea una nueva opción en la base de datos
                Opcion::create([
                    'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                    'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                    'posicionOpcion' => $posicion, // Establece la posición de la opción
                ]);
                $posicion++; // Incrementa la posición para la siguiente opción
            }
        }

        // Si el tipo de pregunta es "Escala numérica"
        if ($request->input('idTipoPregunta') == 'Escala numérica') {
            $rangoMaximo = $request->input('escalaNumerica'); // Obtiene el rango máximo proporcionado por el usuario

            // Valida que el rango máximo sea válido
            if (!is_numeric($rangoMaximo) || $rangoMaximo < 1) {
                return back()->withErrors(['escalaNumerica' => 'Debe proporcionar un rango máximo válido para la escala numérica.']);
            }

            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            // Para cada número en el rango
            for ($i = 1; $i <= $rangoMaximo; $i++) {
                // Crea una nueva opción en la base de datos
                Opcion::create([
                    'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                    'contenidoOpcion' => $i, // Establece el contenido de la opción
                    'posicionOpcion' => $i, // Establece la posición de la opción
                ]);
            }
        }

        // Si el tipo de pregunta es "Preguntas mixtas"
        if ($request->input('idTipoPregunta') == 'Preguntas mixtas') {
            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            $posicion = 1; // Inicializa la posición de la opción
            $opciones = preg_split('/,(?![^\(]*\))/', $request->input('opcionesMixtas'));
            $opciones = array_map(function($opcion) {
                return trim(str_replace(['(', ')'], '', $opcion));
            }, $opciones);

            // Para cada opción proporcionada por el usuario
            foreach ($opciones as $contenidoOpcion) {
                // Crea una nueva opción en la base de datos
                Opcion::create([
                    'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                    'contenidoOpcion' => $contenidoOpcion, // Establece el contenido de la opción
                    'posicionOpcion' => $posicion, // Establece la posición de la opción
                ]);
                $posicion++; // Incrementa la posición para la siguiente opción
            }

            // Verifica si la última opción ingresada por el usuario es "Otra"
            if (trim(end($opciones)) != 'Otra') {
                // Si no es "Otra", verifica si ya existe una opción "Otra"
                $otraOpcion = Opcion::where('idPregunta', $pregunta->idPregunta)
                                     ->where('contenidoOpcion', 'Otra')
                                     ->first();
                // Si no existe, crea una opción adicional llamada "Otra"
                if (!$otraOpcion) {
                    Opcion::create([
                        'idPregunta' => $pregunta->idPregunta, // Asocia la opción con la pregunta
                        'contenidoOpcion' => 'Otra', // Establece el contenido de la opción
                        'posicionOpcion' => $posicion, // Establece la posición de la opción
                    ]);
                }
            }
        }

        return redirect()->route('preguntas.index', ['idEncuesta' => $request->idEncuesta])
            ->with('success', 'Pregunta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idEncuesta, Preguntas $preguntas)
    {
        $idEncuesta = $preguntas->idEncuesta;

        // Elimina las opciones que hacen referencia a esta pregunta
        Opcion::where('idPregunta', $preguntas->idPregunta)->delete();

        // Ahora puedes eliminar la pregunta
        $preguntas->delete();

        return redirect()->route('preguntas.index', ['idEncuesta' => $idEncuesta])
            ->with('success', 'Pregunta eliminada exitosamente.');
    }
}
