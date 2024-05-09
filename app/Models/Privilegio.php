<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    use HasFactory;

    protected $table = 'privilegios';
    protected $primaryKey = 'idPrivilegio';
    protected $fillable = ['idRol', 'nombrePrivilegio', 'descripcionPrivilegio'];

    // Relación con el modelo Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'idRol');
    }
}
