<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadesEvidencias extends Model
{
    use HasFactory;

    protected $table = 'actividades_evidencias';

    public $timestamps = false;

    protected $fillable = [
        'acti_codigo', #clave foranea
        'acen_codigo',
        'acen_nombre',
        'acen_descripcion',
        'acen_ruta',
        'acen_mime',
        'acen_nombre_origen',
        'acen_creado',
        'acen_actualizado',
        'acen_vigente',
        'acen_rut_mod',
        'acen_rol_mod'
    ];
}
