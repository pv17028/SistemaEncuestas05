<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    protected $table = 'respuestas';
    protected $primaryKey = 'id';
    protected $fillable = ['encuesta_id', 'pregunta_id', 'opcion_id', 'respuesta_abierta', 'usuario_id'];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'encuesta_id', 'idEncuesta');
    }

    public function pregunta()
    {
        return $this->belongsTo(preguntas::class, 'pregunta_id', 'idPregunta');
    }

    public function opcion()
    {
        return $this->belongsTo(Opcion::class, 'opcion_id', 'idOpcion');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
}
