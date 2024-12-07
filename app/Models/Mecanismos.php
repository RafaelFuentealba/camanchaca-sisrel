<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mecanismos extends Model {
    use HasFactory;

    protected $table = "mecanismo";

    public $timestamps = false;

    protected $fillable = [
        'meca_nombre',
        'meca_puntaje',
        'meca_creado',
        'meca_actualizado',
        'meca_vigente',
        'meca_rut_mod',
        'meca_rol_mod'
    ];
}
