<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoEncuesta extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'resultados_encuesta';

    // Clave primaria personalizada
    protected $primaryKey = 'idResultadoEncuesta';

    // Campos que pueden ser asignados en masa
    protected $fillable = [
        'idEncuesta',
        'fechaResultados'
    ];

    // Relación con el modelo Encuesta
    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'idEncuesta');
    }

    // Relación con el modelo Grafico
    public function graficos()
    {
        return $this->hasMany(Grafico::class, 'idResultadoEncuesta');
    }
}
