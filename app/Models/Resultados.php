<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultados extends Model {
    use HasFactory;

    protected $table = "resultados";

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'resu_nombre',
        'resu_cuantificacion_inicial',
        'resu_cuantificacion_final',
        'resu_creado',
        'resu_actualizado',
        'resu_vigente',
        'resu_rut_mod',
        'resu_rol_mod'
    ];
}
