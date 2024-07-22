<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iniciativas extends Model
{
    protected $table = 'iniciativas';

    public $timestamps = false;

    protected $fillable = [
        'inic_nombre',
        'inic_objetivo_dec',
        'inic_fecha_inicio',
        'inic_fecha_fin',
        'inic_nombre_responsable',
        'inic_cargo_responsable',
        'inic_inrel',
        'inic_aprobada',
        'inic_creado',
        'inic_actualizado',
        'inic_vigente',
        'inic_rut_mod',
        'inic_rol_mod',
        /* foráneas */
        'pila_codigo',
        'frec_codigo',
        'foim_codigo',
        'conv_codigo',
        'meca_codigo',
    ];
}
