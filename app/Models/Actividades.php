<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    public $timestamps = false;

    protected $fillable = [
        'acti_codigo',
        'orga_codigo',
        'acti_nombre',
        'acti_fecha',
        'acti_acuerdos',
        'acti_fecha_cumplimiento',
        'acti_avance',
        'acti_creado',
        'acti_actualizado',
        'acti_vigente',
        'acti_rut_creado',
        'acti_rut_mod',
        'acti_rol_mod'
    ];
}
