<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    use HasFactory;

    protected $table = 'privilegios';
    protected $primaryKey = 'idPrivilegio';
    protected $fillable = ['nombrePrivilegio', 'descripcionPrivilegio', 'url'];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_privilegio', 'idPrivilegio', 'idRol');
    }
}
