<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Exportacion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EncuestaExport;
use App\Exports\EncuestaGeneralExport;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;

class ExportacionController extends Controller
{
       // Mostrar todas las encuestas
    public function index()
    {
        $idUsuario = auth()->user()->id; // Obtener el ID del usuario autenticado
        $encuestas = Encuesta::where('idUsuario', $idUsuario)->get();
        return view('exportacion.index', compact('encuestas'));
    }

    /*public function store(Request $request)
    {
        $exportacion = new Exportacion;
        $exportacion->idGrafico = 1;
        $exportacion->idResultadoEncuesta = $request->idResultadoEncuesta;
        $exportacion->tipoExportacion = $request->tipoExportacion;
        $exportacion->save();

        return redirect()->route('exportacion.index')->with('success', 'Exportación finalizada.');
    }*/

    // Método para exportar a Excel
    public function exportToExcel($idEncuesta)
    {
        /*$exportacion = new Exportacion;
        $exportacion->idGrafico = 1;
        $exportacion->idResultadoEncuesta = $request->idResultadoEncuesta;
        $exportacion->tipoExportacion = "Excel";
        $exportacion->save();*/
        return Excel::download(new EncuestaExport($idEncuesta), 'Reporte.xlsx');
    }

    // Método para exportar a PDF
    public function exportToPDF($idEncuesta)
    {
        $export = new EncuestaExport($idEncuesta);
        $pdf = $export->pdf($idEncuesta);
        //return $pdf->download('Reporte.pdf');
        return $pdf->stream('Reporte.pdf');
    }

    public function reporteGeneralPdf()
    {  
        //Total de encuestas por usuario
        $encuestas = DB::table('encuestas')
        ->join('users', 'users.id', '=', 'encuestas.idUsuario')
        ->select(
            'users.nombre',
            'users.apellido',
            'users.username',
            'users.correoElectronico',
            //Recordar que los campos en PostgreSQL deben estar con comillas dobles para sentencias en RAW
            DB::raw('count(encuestas."idEncuesta") as total_encuestas') 
        )
        //Filtrar por encuestas creadas en los últimos 30 días
        ->where('encuestas.created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('users.nombre', 'users.apellido', 'users.username', 'users.correoElectronico')
        ->get();
        
        //Consulta del total de preguntas por encuesta
        $preguntas = DB::table('encuestas')
        ->join('preguntas', 'preguntas.idEncuesta', '=', 'encuestas.idEncuesta')
        ->join('users', 'users.id', '=', 'encuestas.idUsuario')
        ->select(
            'users.username',
            'encuestas.titulo',
            'encuestas.descripcionEncuesta',
            DB::raw('count(preguntas."idPregunta") as total_preguntas')
        )
        //Filtrar por encuestas creadas en los últimos 30 días
        ->where('encuestas.created_at', '>=', Carbon::now()->subDays(30))
        ->groupBy('encuestas.idEncuesta', 'users.username', 'encuestas.titulo')
        ->get();

        $pdf = PDF::loadView('exportacion.reporte', compact('encuestas','preguntas'));

        return $pdf->stream('Reporte_General.pdf');
    }
}
