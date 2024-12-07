<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donaciones extends Model
{
    use HasFactory;

    protected $table = 'donaciones';

    public $timestamps = false;

    protected $fillable = [
        'orga_codigo',
        'pila_codigo',
        'diri_codigo',
        'comu_codigo',
        'dona_codigo',
        'dona_motivo',
        'dona_nombre_solicitante',
        'dona_cargo_solicitante',
        'dona_persona_aprueba',
        'dona_descripcion',
        'dona_monto',
        'dona_fecha_entrega',
        'dona_persona_recepciona',
        'dona_estado',
        'dona_form_autorizacion',
        'dona_declaracion_jurada',
        'dona_tipo_aporte',
        'dona_creado',
        'dona_actualizado',
        'dona_vigente',
        'dona_rut_mod',
        'dona_rol_mod',
    ];
}
