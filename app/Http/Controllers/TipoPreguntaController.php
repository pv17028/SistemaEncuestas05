<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoPregunta;
use Illuminate\Support\Facades\DB;

class TipoPreguntaController extends Controller
{
    /**
     * Muestra una lista de tipos de preguntas.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $tiposPreguntas = TipoPregunta::all();
        return view('tiposPreguntas.index', compact('tiposPreguntas'));
    }

    /**
     * Muestra el formulario para crear un nuevo tipo de pregunta.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('tiposPreguntas.create');
    }

    /**
     * Almacena un nuevo tipo de pregunta en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'tipoPregunta' => 'required|string|max:50',
            'descripcionTipo' => 'required|string|max:300',
        ]);

        $nombreTipoPregunta = $request->input('tipoPregunta');
        $descripcionTipoPregunta = $request->input('descripcionTipo');

        //validar que no exista un tipo de pregunta con el mismo nombre
        $tipoPregunta = DB::table('tipo_preguntas')
            ->where('nombreTipoPregunta', $nombreTipoPregunta)
            ->first();

        if ($tipoPregunta) {
            return redirect()->route('tiposPreguntas.create')
                ->with('error', 'Ya existe un tipo de pregunta con el nombre especificado.');
        }

        DB::table('tipo_preguntas')->insert([
            'nombreTipoPregunta' => $nombreTipoPregunta,
            'descripcionTipoPregunta' => $descripcionTipoPregunta,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tiposPreguntas.index')
            ->with('success', 'Tipo de pregunta creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar el tipo de pregunta especificado.
     *
     * @param  \App\Models\TipoPregunta  $tipoPregunta
     * @return \Illuminate\Http\Response
     */

    public function edit(TipoPregunta $tipoPregunta)
    {
        return view('tiposPreguntas.edit', compact('tipoPregunta'));
    }

    /**
     * Actualiza el tipo de pregunta especificado en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoPregunta  $tipoPregunta
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, TipoPregunta $tipoPregunta)
    {
        $request->validate([
            'tipoPregunta' => 'required|string|max:50',
            'descripcionTipo' => 'required|string|max:300',
        ]);

        $nombreTipoPregunta = $request->input('tipoPregunta');
        $descripcionTipoPregunta = $request->input('descripcionTipo');

        DB::table('tipo_preguntas')
            ->where('idTipoPregunta', $tipoPregunta->idTipoPregunta)
            ->update([
                'nombreTipoPregunta' => $nombreTipoPregunta,
                'descripcionTipoPregunta' => $descripcionTipoPregunta,
                'updated_at' => now(),
            ]);

        return redirect()->route('tiposPreguntas.index')
            ->with('success', 'Tipo de pregunta actualizado exitosamente.');
    }

    /**
     * Elimina el tipo de pregunta especificado de la base de datos.
     *
     * @param  \App\Models\TipoPregunta  $tipoPregunta
     * @return \Illuminate\Http\Response
     */

    public function destroy(TipoPregunta $tipoPregunta)
    {
        DB::table('tipo_preguntas')
            ->where('idTipoPregunta', $tipoPregunta->idTipoPregunta)
            ->delete();

        return redirect()->route('tiposPreguntas.index')
            ->with('success', 'Tipo de pregunta eliminado exitosamente.');
    }

    /**
     * Muestra el tipo de pregunta especificado.
     *
     * @param  \App\Models\TipoPregunta  $tipoPregunta
     * @return \Illuminate\Http\Response
     */

    public function show(TipoPregunta $tipoPregunta)
    {
        return view('tiposPreguntas.show', compact('tipoPregunta'));
    }

}
