<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostosEspecies extends Model {
    use HasFactory;

    protected $table = 'costos_especies';

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'enti_codigo',
        'coes_nombre',
        'coes_valorizacion',
        'coes_creado',
        'coes_actualizado',
        'coes_vigente',
        'coes_rut_mod',
        'coes_rol_mod'
    ];
}
