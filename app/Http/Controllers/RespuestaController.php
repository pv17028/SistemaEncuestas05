<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encuesta;
use App\Models\Respuesta;

class RespuestaController extends Controller
{
    public function store(Request $request, $id)
    {
        $encuesta = Encuesta::where('idEncuesta', $id)->first();
        if (!$encuesta) {
            return redirect()->back()->with('error', 'Encuesta no encontrada.');
        }

        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'pregunta')) {
                $pregunta_id = str_replace('pregunta', '', $key);
                Respuesta::create([
                    'encuesta_id' => $encuesta->idEncuesta,
                    'pregunta_id' => $pregunta_id,
                    'opcion_id' => $value,
                    'usuario_id' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('encuestas.show', $id)->with('success', 'Tu respuesta ha sido guardada.');
    }
}