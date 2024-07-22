<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriasClima extends Model
{
    use HasFactory;

    protected $table = 'categorias_clima';

    public $timestamps = false;

    protected $fillable = [
        'cacl_codigo',
        'cacl_nombre',
        'cacl_creado',
        'cacl_actualizado',
        'cacl_vigente',
        'cacl_rut_mod',
        'cacl_rol_mod'
    ];
}
