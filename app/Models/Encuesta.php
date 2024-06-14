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
        'compartida',
        'compartirConTodos',
        'compartida_con',
        'es_anonima',
        'logo',
        'color_principal',
        'color_secundario',
        'color_terciario',
        'color_cuarto',
        'color_quinto',
        'color_sexto',
        'color_septimo',
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

    public function respuestasCount()
    {
        return $this->hasMany(Respuesta::class, 'encuesta_id')
            ->selectRaw('encuesta_id, count(*) as total')
            ->groupBy('encuesta_id');
    }

    // Relación con el modelo CorreoNotificacion
    public function correosNotificacion()
    {
        return $this->hasMany(CorreoNotificacion::class, 'idEncuesta');
    }

    // Relación con el modelo Pregunta
    public function preguntas()
    {
        return $this->hasMany(preguntas::class, 'idEncuesta');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'encuesta_id', 'idEncuesta');
    }

    public function encuesta_usuario()
    {
        return $this->hasOne(EncuestaUsuario::class, 'encuesta_id');
    }
}
