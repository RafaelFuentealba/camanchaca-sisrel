<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonacionesEvidencias extends Model
{
    use HasFactory;

    protected $table = 'donaciones_evidencias';

    public $timestamps = false;

    protected $fillable = [
        'dona_codigo', #clave foranea
        'doen_codigo',
        'doen_nombre',
        'doen_descripcion',
        'doen_ruta',
        'doen_mime',
        'doen_nombre_origen',
        'doen_creado',
        'doen_actualizado',
        'doen_vigente',
        'doen_rut_mod',
        'doen_rol_mod'
    ];
}
