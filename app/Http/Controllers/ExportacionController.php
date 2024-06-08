<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Exportacion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EncuestaExport;

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
    public function exportToExcel()
    {
        /*$exportacion = new Exportacion;
        $exportacion->idGrafico = 1;
        $exportacion->idResultadoEncuesta = $request->idResultadoEncuesta;
        $exportacion->tipoExportacion = "Excel";
        $exportacion->save();*/
        return Excel::download(new EncuestaExport, 'Reporte.xlsx');
    }

    // Método para exportar a PDF
    public function exportToPDF()
    {
        $pdf = EncuestaExport::pdf();
        //return $pdf->download('Reporte.pdf');
        return $pdf->stream('Reporte.pdf');
    }
}
