<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table = 'encuestas';

    protected $primaryKey = 'idEncuesta';

    protected $fillable = [
        'idUsuario',
        'titulo',
        'objetivo',
        'descripcionEncuesta',
        'grupoMeta',
        'fechaVencimiento',
    ];

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');
    }

    // Relación con el modelo ResultadoEncuesta
    public function resultadoEncuesta()
    {
        return $this->hasOne(ResultadoEncuesta::class, 'idEncuesta');
    }

    // Relación con el modelo CorreoNotificacion
    public function correosNotificacion()
    {
        return $this->hasMany(CorreoNotificacion::class, 'idEncuesta');
    }

    // Relación con el modelo Pregunta
    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'idEncuesta');
    }
}
