<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exportacion extends Model
{
    use HasFactory;

    protected $table = 'exportaciones'; // Nombre de la tabla

    protected $primaryKey = 'idExport'; // Nombre de la PK

    protected $fillable = [
        'idGrafico',
        'idResultadoEncuesta',
        'tipoExportacion',
    ];

    public $timestamps = true; // Habilitar los timestamps

    public function grafico()
    {
        return $this->belongsTo(Grafico::class, 'idGrafico');
    }

    public function resultadoEncuesta()
    {
        return $this->belongsTo(ResultadoEncuesta::class, 'idResultadoEncuesta');
    }
}
