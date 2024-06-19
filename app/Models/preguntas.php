<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class preguntas extends Model
{
    use HasFactory;
    protected $table = 'preguntas';

    protected $primaryKey = 'idPregunta';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idEncuesta',
        'idTipoPregunta',
        'contenidoPregunta',
        'descripcionPregunta',
        'criterioValidacion',
        'posicionPregunta',
    ];


    public function tipoPregunta()
    {
        return $this->belongsTo(TipoPregunta::class, 'idTipoPregunta');
    }

    public function opciones()
    {
        return $this->hasMany(Opcion::class, 'idPregunta');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'pregunta_id');
    }
}
