<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistentesActividades extends Model
{
    use HasFactory;

    protected $table = 'asistentes_actividades';

    public $timestamps = false;

    protected $fillable = [
        'acti_codigo',
        'asis_codigo',
        'asac_creado',
        'asac_actualizado',
        'asac_vigente',
        'asac_rut_mod',
        'asac_rol_mod'
    ];
}
