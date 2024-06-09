<?php

namespace App\Exports;

use App\Models\Exportacion;
use App\Models\Encuesta;
use App\Models\ResultadoEncuesta;
use App\Models\preguntas;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF;

class EncuestaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
        @return \Barryvdh\DomPDF\Facade\PDF
    */
    public function collection()
    {
        // Consulta SQL personalizada
        /*$encuestas = DB::table('exportaciones as EX')
            ->join('resultado_encuesta as R', 'EX.idResultadoEncuesta', '=', 'R.idResultadoEncuesta')
            ->join('encuestas as E', 'R.idEncuesta', '=', 'E.idEncuesta')
            ->join('users as U', 'U.id', '=', 'E.idUsuario')
            ->select('R.fechaResultados', 'U.nombre', 'U.apellido', 'U.username', 'U.correoElectronico', 'E.titulo', 'E.descripcionEncuesta')
            ->get();*/
   
        $encuestas = DB::table('encuestas')
        ->join('users', 'users.id', '=', 'encuestas.idUsuario')
        ->join('preguntas', 'preguntas.idEncuesta', '=', 'encuestas.idEncuesta')
        ->join('opcions', 'opcions.idPregunta', '=', 'preguntas.idPregunta')
        ->selectRaw('DISTINCT ON (preguntas."contenidoPregunta") 
                     users."nombre", users."apellido", users."username", users."correoElectronico",
                     encuestas."titulo", encuestas."descripcionEncuesta",
                     preguntas."contenidoPregunta", opcions."contenidoOpcion"')
        ->orderBy('preguntas.contenidoPregunta')
        ->get();

        return $encuestas;
    }
    
    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Nombre de Usuario',
            'Correo Electrónico',
            'Título de la encuesta',
            'Descripción de la encuesta',
            'Contenido de la pregunta',
            'Respuesta seleccionada'
        ];
    }

    public static function pdf()
    {
        // Consulta SQL personalizada
        /*$encuestas = DB::table('exportaciones as EX')
            ->join('resultado_encuesta as R', 'EX.idResultadoEncuesta', '=', 'R.idResultadoEncuesta')
            ->join('encuestas as E', 'R.idEncuesta', '=', 'E.idEncuesta')
            ->join('users as U', 'U.id', '=', 'E.idUsuario')
            ->select('R.fechaResultados', 'U.nombre', 'U.apellido', 'U.username', 'U.correoElectronico', 'E.titulo', 'E.descripcionEncuesta')
            ->get();*/

            $encuestas = DB::table('encuestas')
            ->join('users', 'users.id', '=', 'encuestas.idUsuario')
            ->join('preguntas', 'preguntas.idEncuesta', '=', 'encuestas.idEncuesta')
            ->join('opcions', 'opcions.idPregunta', '=', 'preguntas.idPregunta')
            ->selectRaw('DISTINCT ON (preguntas."contenidoPregunta") 
                         users."nombre", users."apellido", users."username", users."correoElectronico",
                         encuestas."titulo", encuestas."descripcionEncuesta",
                         preguntas."contenidoPregunta", opcions."contenidoOpcion"')
            ->orderBy('preguntas.contenidoPregunta')
            ->get();
        // Generar el PDF
        $pdf = Pdf::loadView('exportacion.pdf', ['encuestas' => $encuestas]);

        return $pdf;
    }
}
