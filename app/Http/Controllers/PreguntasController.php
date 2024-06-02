<?php

namespace App\Http\Controllers;

use App\Models\preguntas;
use Illuminate\Http\Request;
use App\Models\TipoPregunta;
use App\Models\Encuesta;
use App\Models\Opcion;
use Illuminate\Support\Facades\Validator;

class PreguntasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idEncuesta)
    {
        $encuesta = Encuesta::find($idEncuesta);
        $preguntas = preguntas::where('idEncuesta', $idEncuesta)->get();
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
            'criterioValidacion' => 'required|string|max:256',
            'posicionPregunta' => 'integer',
        ]);

        $pregunta = preguntas::where('contenidoPregunta', $request->input('contenidoPregunta'))->first();

        if ($pregunta) {
            return redirect()->route('preguntas.create', ['idEncuesta' => $request->idEncuesta])
                ->with('error', 'La pregunta ya existe.');
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
        $pregunta->save();

        // Si el tipo de pregunta es "Preguntas de elección múltiple"
        if ($request->input('idTipoPregunta') == 'Preguntas de elección múltiple') {
            $posicion = 1; // Inicializa la posición de la opción
            $opciones = explode(',', $request->input('opcionesMultiple')); // Divide las opciones proporcionadas por el usuario en un array

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

        // Si el tipo de pregunta es "Preguntas mixtas"
        if ($request->input('idTipoPregunta') == 'Preguntas mixtas') {
            $posicion = 1; // Inicializa la posición de la opción
            $opciones = explode(',', $request->input('opcionesMixtas')); // Divide las opciones proporcionadas por el usuario en un array

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
        $preguntas = Preguntas::find($idPregunta);
        if (!$preguntas) {
            return redirect()->back()->with('error', 'Pregunta no encontrada.');
        }

        // Cargar las opciones para la pregunta
        $preguntas->load('opciones');

        return view('preguntas.show', compact('preguntas'));
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
            'criterioValidacion' => 'required|string|max:256',
            'posicionPregunta' => 'integer',
        ]);

        $pregunta = preguntas::find($id);

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

        // Si el tipo de pregunta es "Preguntas de elección múltiple"
        if ($request->input('idTipoPregunta') == 'Preguntas de elección múltiple') {
            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            $posicion = 1; // Inicializa la posición de la opción
            $opciones = explode(',', $request->input('opcionesMultiple')); // Divide las opciones proporcionadas por el usuario en un array

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

        // Si el tipo de pregunta es "Preguntas mixtas"
        if ($request->input('idTipoPregunta') == 'Preguntas mixtas') {
            // Elimina las opciones existentes
            Opcion::where('idPregunta', $pregunta->idPregunta)->delete();

            $posicion = 1; // Inicializa la posición de la opción
            $opciones = explode(',', $request->input('opcionesMixtas')); // Divide las opciones proporcionadas por el usuario en un array

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
