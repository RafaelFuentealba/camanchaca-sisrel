<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class InicitivasUnidades extends Controller
{
    use HasFactory;

    protected $table = "iniciativas_unidades";

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'unid_codigo',
        'inun_creado',
        'inun_actualizado',
        'inun_vigente',
        'inun_rut_mod',
        'inun_rol_mod'
    ];
}
