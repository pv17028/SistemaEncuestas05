<?php

namespace App\Exports;

use App\Models\Exportacion;
use App\Models\Encuesta;
use App\Models\ResultadoEncuesta;
use App\Models\preguntas;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EncuestaExport implements FromCollection, WithHeadings
{
    /*
    * @return \Illuminate\Support\Collection
      @return \Barryvdh\DomPDF\Facade\PDF
    */
    protected $idResultadoEncuesta;

    public function __construct($idResultadoEncuesta)
    {
        $this->idResultadoEncuesta = $idResultadoEncuesta;
    }

    public function collection()
    {
    //Consulta SQL personalizada
        $encuestas = DB::table('respuestas')
        ->join('opcions', 'opcions.idOpcion', '=', 'respuestas.opcion_id')
        ->join('preguntas', 'preguntas.idPregunta', '=', 'opcions.idPregunta')
        ->join('encuestas', 'encuestas.idEncuesta', '=', 'preguntas.idEncuesta')
        ->join('users', 'users.id', '=', 'encuestas.idUsuario')
        ->select(
            'users.nombre',
            'users.apellido',
            'users.username',
            'users.correoElectronico',
            'encuestas.titulo',
            'encuestas.descripcionEncuesta',
            'preguntas.contenidoPregunta',
            'opcions.contenidoOpcion'
        )
        ->where('encuestas.idEncuesta', $this->idResultadoEncuesta)
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

    public function pdf($idResultadoEncuesta)
    {
    //Consulta SQL personalizada
    $encuestas = DB::table('respuestas')
    ->join('opcions', 'opcions.idOpcion', '=', 'respuestas.opcion_id')
    ->join('preguntas', 'preguntas.idPregunta', '=', 'opcions.idPregunta')
    ->join('encuestas', 'encuestas.idEncuesta', '=', 'preguntas.idEncuesta')
    ->join('users', 'users.id', '=', 'encuestas.idUsuario')
    ->select(
        'users.nombre',
        'users.apellido',
        'users.username',
        'users.correoElectronico',
        'encuestas.titulo',
        'encuestas.descripcionEncuesta',
        'preguntas.contenidoPregunta',
        'opcions.contenidoOpcion'
    )
    ->where('encuestas.idEncuesta', $this->idResultadoEncuesta)
    ->get();
    // Generar el PDF
    $pdf = Pdf::loadView('exportacion.pdf', ['encuestas' => $encuestas]);

    return $pdf;
    }
}
