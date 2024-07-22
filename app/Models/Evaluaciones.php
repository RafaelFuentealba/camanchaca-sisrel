<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluaciones extends Model {
    use HasFactory;

    protected $table = 'evaluacion';

    public $timestamps = false;

    protected $fillable = [
        'tiev_codigo',
        'inic_codigo',
        'eval_plazos',
        'eval_horarios',
        'eval_infraestructura',
        'eval_equipamiento',
        'eval_conexion_dl',
        'eval_desempenho_responsable',
        'eval_desempenho_participantes',
        'eval_calidad_presentaciones',
        'eval_creado',
        'eval_actualizado',
        'eval_vigente',
        'eval_rut_mod',
        'eval_rol_mod'
    ];
}
