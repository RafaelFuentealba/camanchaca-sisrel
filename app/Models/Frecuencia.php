<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frecuencia extends Model {
    use HasFactory;

    protected $table = "frecuencia";

    public $timestamps = false;

    protected $fillable = [
        'frec_nombre',
        'frec_puntaje',
        'frec_creado',
        'frec_actualizado',
        'frec_vigente',
        'frec_rut_mod',
        'frec_rol_mod'
    ];
}
