<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impactos extends Model {
    use HasFactory;

    protected $table = "impactos";

    public $timestamps = false;

    protected $fillable = [
        'impa_nombre',
        'impa_creado',
        'impa_actualizado',
        'impa_vigente',
        'impa_rut_mod',
        'impa_rol_mod'
    ];
}
