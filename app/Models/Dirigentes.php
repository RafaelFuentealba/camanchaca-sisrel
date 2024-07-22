<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dirigentes extends Model {
    use HasFactory;

    protected $table = 'dirigentes';

    public $timestamps = false;

    protected $fillable = [
        // primary key 
        'diri_codigo',
        // campos
        'diri_nombre', 
        'diri_apellido',
        'diri_telefono',
        'diri_email',
        'diri_cargo',
        'diri_creado',
        'diri_actualizado',
        'diri_vigente',
        'diri_rut_mod',
        'diri_rol_mod'
    ];
}
