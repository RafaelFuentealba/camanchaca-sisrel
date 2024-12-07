<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionPrensa extends Model {
    use HasFactory;

    protected $table = "evaluacion_prensa";

    public $timestamps = false;

    protected $fillable = [
        // clave primaria
        'evpr_codigo',
        // clave foranea
        'regi_codigo',
        // campos de la bd
        'evpr_valor',
        'evpr_creado',
        'evpr_actualizado',
        'evpr_vigente',
        'evpr_rut_mod',
        'evpr_rol_mod'
    ];
}
