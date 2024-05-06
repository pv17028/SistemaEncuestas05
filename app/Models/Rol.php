<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol';
    protected $primaryKey = 'idRol';
    protected $fillable = [
        'nombreRol', 'descripcionRol',
    ];

    /**
     * Define la relación uno a muchos con usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'idRol');
    }

    /**
     * Define la relación uno a muchos con privilegios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function privilegios(): HasMany
    {
        return $this->hasMany(Privilegio::class, 'idRol');
    }
}
