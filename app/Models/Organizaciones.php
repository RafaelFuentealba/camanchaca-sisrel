<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizaciones extends Model
{
    use HasFactory;

    protected $table = 'organizaciones';

    public $timestamps = false;

    protected $fillable = [
        'comu_codigo',
        'ento_codigo',
        'orga_nombre',
        'orga_descripcion',
        'orga_domicilio',
        'orga_geoubicacion',
        'orga_cantidad_socios',
        'orga_fecha_vinculo',
        'orga_creado',
        'orga_actualizado',
        'orga_vigente',
        'orga_rut_mod',
        'orga_rol_mod'
    ];
}
