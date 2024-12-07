<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirigentesOrganizaciones extends Model {
    use HasFactory;

    protected $table = 'dirigentes_organizaciones';

    public $timestamps = false;

    protected $fillable = [
        // primary key
        'diri_codigo',
        // foreing key
        'orga_codigo',
        // campos
        'dior_creado',
        'dior_actualizado',
        'dior_vigente',
        'dior_rut_mod',
        'dior_rol_mod',
    ];
}
