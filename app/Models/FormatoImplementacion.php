<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormatoImplementacion extends Model {
    use HasFactory;

    protected $table = "formato_implementacion";

    public $timestamps = false;

    protected $fillable = [
        'foim_nombre',
        'foim_puntaje',
        'foim_creado',
        'foim_actualizado',
        'foim_vigente',
        'foim_rut_mod',
        'foim_rol_mod'
    ];
}
