<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjetivosDesarrollo extends Model {
    use HasFactory;

    protected $table = "objetivos_desarrollo";

    public $timestamps = false;

    protected $fillable = [
        'obde_nombre',
        'obde_descripcion',
        'obde_ruta_imagen',
        'obde_url',
        'obde_creado',
        'obde_actualizado',
        'obde_vigente',
        'obde_rut_mod',
        'obde_rol_mod'
    ];
}
