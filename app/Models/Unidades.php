<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidades extends Model {
    use HasFactory;

    protected $table = "unidades";

    public $timestamps = false;

    protected $fillable = [
        'unid_nombre',
        'unid_descripcion',
        'unid_responsable',
        'unid_nombre_cargo',
        'unid_geoubicacion',
        'unid_creado',
        'unid_actualizado',
        'unid_vigente',
        'unid_rut_mod',
        'unid_rol_mod',
        //foraneas
        'tuni_codigo',
        'comu_codigo',
        'divi_codigo'
    ];
}
