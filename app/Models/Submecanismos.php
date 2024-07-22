<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submecanismos extends Model {
    use HasFactory;

    protected $table = "submecanismo";

    public $timestamps = false;

    protected $fillable = [
        'meca_codigo',
        'subm_nombre',
        'subm_creado',
        'subm_actualizado',
        'subm_vigente',
        'subm_rut_mod',
        'subm_rol_mod'
    ];
}
