<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class opcion extends Model
{
    use HasFactory;
    protected $table = 'opcions';

    protected $primaryKey = 'idOpcion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idPregunta',
        'contenidoOpcion',
        'posicionOpcion',
    ];

    public function pregunta()
    {
        return $this->belongsTo(pregunta::class, 'idPregunta');
    }



}
