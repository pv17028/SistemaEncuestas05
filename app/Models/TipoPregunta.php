<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPregunta extends Model
{
    use HasFactory;
    protected $table = 'tipo_preguntas';

    protected $primaryKey = 'idTipoPregunta';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nombreTipoPregunta',
        'descripcionTipoPregunta',
    ];

}
