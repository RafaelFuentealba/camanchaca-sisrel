<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participantes extends Model {
    use HasFactory;

    protected $table = "participantes";

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'sube_codigo',
        'part_cantidad_inicial',
        'part_cantidad_final',
        'part_genero_hombre',
        'part_genero_mujer',
        'part_genero_otro',
        'part_etario_ninhos',
        'part_etario_jovenes',
        'part_etario_adultos',
        'part_etario_adumayores',
        'part_procedencia_rural',
        'part_procedencia_urbano',
        'part_nacionalidad_chilena',
        'part_nacionalidad_migrante',
        'part_adscrito_pueblos',
        'part_no_adscrito_pueblos',
        'part_creado',
        'part_actualizado',
        'part_vigente',
        'part_rut_mod',
        'part_rol_mod'
    ];
}
