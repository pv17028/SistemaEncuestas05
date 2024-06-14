<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuestaUsuario extends Model
{
    use HasFactory;

    protected $table = 'encuesta_usuario';

    protected $fillable = ['encuesta_id', 'usuario_id', 'respuesta_ids', 'preguntas_no_respondidas', 'completa'];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'usuario_id');
    }
}