<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionOperaciones extends Model
{
    protected $table = 'evaluacion_operaciones';

    public $timestamps = false;

    protected $fillable = [
        'evop_codigo',
        'unid_codigo',
        'evop_valor',
        'evop_creado',
        'evop_actualizado',
        'evop_vigente',
        'evop_rut_mod',
        'evop_rol_mod'
    ];
}
