<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IniciativasImpactos extends Model {
    use HasFactory;

    protected $table = "iniciativas_impactos";

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'impa_codigo',
        'inim_creado',
        'inim_actualizado',
        'inim_vigente',
        'inim_rut_mod',
        'inim_rol_mod'
    ];
}
