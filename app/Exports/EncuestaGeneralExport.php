<?php

namespace App\Exports;

use App\Models\Exportacion;
use App\Models\Encuesta;
use App\Models\ResultadoEncuesta;
use App\Models\preguntas;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class EncuestaGeneralExport
{
    public function pdf()
    {
        $encuestas = DB::table('encuestas')
            ->join('users', 'users.id', '=', 'encuestas.idUsuario')
            ->join('preguntas', 'preguntas.idEncuesta', '=', 'encuestas.idEncuesta')
            ->select(
                'users.nombre',
                'users.apellido',
                'users.username',
                'users.correoElectronico',
                'encuestas.titulo',
                'encuestas.descripcionEncuesta'
            )
            ->groupBy('encuestas.idEncuesta', 'users.username', 'encuestas.titulo')
            ->get();

        $pdf = PDF::loadView('exportar.general.pdf', compact('encuestas'));
    }
}
