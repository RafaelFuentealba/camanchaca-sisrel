<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunas extends Model
{
    use HasFactory;

    protected $table = 'comunas';

    public $timestamps = false;

    protected $fillable = [
        'regi_codigo',
        'comu_codigo',
        'comu_nombre',
        'comu_geoubicacion',
        'comu_geolimites',
        'comu_creado',
        'comu_actualizado',
        'comu_vigente',
        'comu_rut_mod',
        'comu_rol_mod'
    ];
}
