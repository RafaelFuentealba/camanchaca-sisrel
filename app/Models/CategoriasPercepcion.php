<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriasPercepcion extends Model
{
    use HasFactory;

    protected $table = 'categorias_percepcion';

    public $timestamps = false;

    protected $fillable = [
        'cape_codigo',
        'cape_nombre',
        'cape_creado',
        'cape_actualizado',
        'cape_vigente',
        'cape_rut_mod',
        'cape_rol_mod'
    ];
}
