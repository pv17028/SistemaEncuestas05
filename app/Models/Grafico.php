<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grafico extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'graficos';

    // Clave primaria personalizada
    protected $primaryKey = 'idGrafico';

    // Campos que pueden ser asignados en masa
    protected $fillable = [
        'idResultadoEncuesta',
        'tipoGrafico'
    ];

    // Relación con el modelo ResultadoEncuesta
    public function resultadoEncuesta()
    {
        return $this->belongsTo(ResultadoEncuesta::class, 'idResultadoEncuesta');
    }
}
