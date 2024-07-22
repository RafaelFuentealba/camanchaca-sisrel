<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IniciativasOds extends Model {
    use HasFactory;

    protected $table = "iniciativas_ods";

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'obde_codigo',
        'inod_creado',
        'inod_actualizado',
        'inod_vigente',
        'inod_rut_mod',
        'inod_rol_mod'
    ];
}
