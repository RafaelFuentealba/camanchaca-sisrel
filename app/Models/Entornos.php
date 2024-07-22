<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entornos extends Model {
    use HasFactory;

    protected $table = 'entornos';

    public $timestamps = false;

    protected $fillable = [
        'ento_nombre',
        'ento_ruta_icono',
        'ento_creado',
        'ento_actualizado',
        'ento_vigente',
        'ento_rut_mod',
        'ento_rol_mod'
    ];
}
