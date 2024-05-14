<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'id'; // Clave primaria

    public $incrementing = true; // Indica que la clave primaria es autoincremental

    protected $keyType = 'int'; // Tipo de la clave primaria

    public $timestamps = false; // Indica que no se manejan los timestamps created_at y updated_at

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idRol',
        'idBloqueUsuario',
        'nombre',
        'apellido',
        'correoElectronico',
        'fechaNacimiento',
        'username',
        'password',
        'intentosFallidos',
        'ultimaAcceso',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ultimaAcceso' => 'datetime',
        ];
    }

    // Definición de las relaciones con los otros modelos

    public function role()
    {
        return $this->belongsTo(Rol::class, 'idRol');
    }

    public function bloqueosUsuario()
    {
        return $this->hasMany(BloqueoUsuario::class, 'user_id', 'id');
    }

    public function isAdmin()
    {
        return $this->role && $this->role->nombreRol === 'admin';
    }
}
