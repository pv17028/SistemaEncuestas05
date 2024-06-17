<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Encuesta;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*$encuestas = DB::table('encuestas')
        ->select(
            DB::raw('EXTRACT(MONTH FROM encuestas."created_at") as mes'),
            DB::raw('COUNT(*) as cantidad_encuestas'))
        ->where(DB::raw('EXTRACT(YEAR FROM encuestas."created_at")'), '=', 2024)
        ->groupBy('mes')
        ->get();*/

        $userId = auth()->user()->id;
        $isAdmin = auth()->user()->role->nombreRol == 'admin';

        $encuestas = DB::select("
            WITH meses AS (
                SELECT 1 as mes UNION ALL
                SELECT 2 UNION ALL
                SELECT 3 UNION ALL
                SELECT 4 UNION ALL
                SELECT 5 UNION ALL
                SELECT 6 UNION ALL
                SELECT 7 UNION ALL
                SELECT 8 UNION ALL
                SELECT 9 UNION ALL
                SELECT 10 UNION ALL
                SELECT 11 UNION ALL
                SELECT 12
            )
            SELECT 
                TO_CHAR(TO_DATE(meses.mes::text, 'MM'), 'Month') as mes, 
                COALESCE(COUNT(encuestas.\"idEncuesta\"), 0) as cantidad_encuestas
            FROM 
                meses
            LEFT JOIN 
                encuestas 
                ON EXTRACT(MONTH FROM encuestas.\"created_at\") = meses.mes 
                AND EXTRACT(YEAR FROM encuestas.\"created_at\") = 2024
                " . ($isAdmin ? "" : "AND encuestas.\"idUsuario\" = $userId") . "
            GROUP BY 
                meses.mes, TO_CHAR(TO_DATE(meses.mes::text, 'MM'), 'Month')
            ORDER BY 
                meses.mes;
        ");

        $encuestasUsuario = DB::select("
            WITH meses AS (
                SELECT 1 as mes UNION ALL
                SELECT 2 UNION ALL
                SELECT 3 UNION ALL
                SELECT 4 UNION ALL
                SELECT 5 UNION ALL
                SELECT 6 UNION ALL
                SELECT 7 UNION ALL
                SELECT 8 UNION ALL
                SELECT 9 UNION ALL
                SELECT 10 UNION ALL
                SELECT 11 UNION ALL
                SELECT 12
            )
            SELECT 
                TO_CHAR(TO_DATE(meses.mes::text, 'MM'), 'Month') as mes, 
                COALESCE(COUNT(encuestas.\"idEncuesta\"), 0) as cantidad_encuestas
            FROM 
                meses
            LEFT JOIN 
                encuestas 
                ON EXTRACT(MONTH FROM encuestas.\"created_at\") = meses.mes 
                AND EXTRACT(YEAR FROM encuestas.\"created_at\") = 2024
                AND encuestas.\"idUsuario\" = $userId
            GROUP BY 
                meses.mes, TO_CHAR(TO_DATE(meses.mes::text, 'MM'), 'Month')
            ORDER BY 
                meses.mes;
        ");
        
        $monthNames = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre',
        ];

        foreach ($encuestas as $encuesta) {
            $encuesta->mes = $monthNames[trim($encuesta->mes)];
        }

        foreach ($encuestasUsuario as $encuesta) {
            $encuesta->mes = $monthNames[trim($encuesta->mes)];
        }

        $usuarios = User::withCount('encuestas')
            ->orderBy('encuestas_count', 'desc')
            ->take(10)
            ->get();

        foreach ($usuarios as $usuario) {
            $usuario->encuesta_mas_respondida = $usuario->encuestas()
                ->join('encuesta_usuario', 'encuestas.idEncuesta', '=', 'encuesta_usuario.encuesta_id')
                ->select('encuestas.*', DB::raw('count(encuesta_usuario.id) as respuestas_count'))
                ->groupBy('encuestas.idEncuesta')
                ->orderBy('respuestas_count', 'desc')
                ->first();
        }

        $usuariosEncuestas = Encuesta::where('idUsuario', auth()->id())
                    ->join('encuesta_usuario', 'encuestas.idEncuesta', '=', 'encuesta_usuario.encuesta_id')
                    ->select('encuestas.*', DB::raw('count(encuesta_usuario.id) as respuestas_count'))
                    ->groupBy('encuestas.idEncuesta')
                    ->orderBy('respuestas_count', 'desc')
                    ->take(10)
                    ->get();

        return view('home', compact('encuestas', 'usuarios', 'encuestasUsuario', 'usuariosEncuestas'));
    }
}
