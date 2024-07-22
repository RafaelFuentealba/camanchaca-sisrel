<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesUsuarios extends Model {
    use HasFactory;

    protected $table = "roles_usuarios";

    public $timestamps = false;

    protected $fillable = [
        'rous_nombre',
        'rous_creado',
        'rous_actualizado',
        'rous_vigente',
        'rous_rut_mod',
        'rous_rol_mod'
    ];
}
