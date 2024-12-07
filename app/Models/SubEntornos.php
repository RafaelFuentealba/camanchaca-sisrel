<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subentornos extends Model {
    use HasFactory;

    protected $table = 'subentornos';

    public $timestamps = false;

    protected $fillable = [
        'ento_codigo',
        'sube_nombre',
        'sube_creado',
        'sube_actualizado',
        'sube_vigente',
        'sube_rut_mod',
        'sube_rol_mod'
    ];
}
