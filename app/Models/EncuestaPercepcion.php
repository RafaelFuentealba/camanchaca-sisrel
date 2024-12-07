<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuestaPercepcion extends Model
{
    use HasFactory;

    protected $table = 'encuesta_percepcion';

    public $timestamps = false;

    protected $fillable = [
        'enpe_codigo',
        'regi_codigo',
        'comu_codigo',
        'cape_codigo',
        'enpe_anho',
        'enpe_puntaje',
        'enpe_creado',
        'enpe_actualizado',
        'enpe_vigente',
        'enpe_rut_mod',
        'enpe_rol_mod'
    ];
}
