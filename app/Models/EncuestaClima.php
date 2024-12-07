<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncuestaClima extends Model
{
    use HasFactory;

    protected $table = 'encuesta_clima';

    public $timestamps = false;

    protected $fillable = [
       // primary key
        'encl_codigo',
        // claves foraneas
        'regi_codigo',
        'comu_codigo',
        'cacl_codigo',
        // elementos oWo
        'encl_anho',
        'encl_puntaje',
        'encl_creado',
        'encl_actualizado',
        'encl_vigente',
        'encl_rut_mod',
        'encl_rol_mod'
    ];
}
