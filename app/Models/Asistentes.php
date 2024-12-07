<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistentes extends Model
{
    use HasFactory;

    protected $table = 'asistentes';

    public $timestamps = false;

    protected $fillable = [
        'diri_codigo',
        'asis_nombre',
        'asis_apellido',
        'asis_creado',
        'asis_actualizado',
        'asis_vigente',
        'asis_rut_mod',
        'asis_rol_mod'
    ];
}
