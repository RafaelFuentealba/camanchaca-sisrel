<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvaluacion extends Model
{
    use HasFactory;

    protected $table = "tipo_evaluacion";

    public $timestamps = false;

    protected $fillable = [
        'tiev_codigo',
        'tiev_nombre',
        'tiev_descripcion',
        'tiev_creado',
        'tiev_actualizado',
        'tiev_vigente',
        'tiev_rut_mod',
        'tiev_rol_mod'
    ];
}
