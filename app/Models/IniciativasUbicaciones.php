<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IniciativasUbicaciones extends Model {
    use HasFactory;

    protected $table = "iniciativas_ubicaciones";

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'comu_codigo',
        'inub_creado',
        'inub_actualizado',
        'inub_vigente',
        'inub_rut_mod',
        'inub_rol_mod'
    ];
}
