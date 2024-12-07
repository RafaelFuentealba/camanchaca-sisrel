<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRrhh extends Model
{
    use HasFactory;
    protected $table = "tipo_rrhh";
    protected $primaryKey = 'tirh_codigo';
    public $timestamps = false;

    protected $fillable = [
        'tirh_codigo',
        'tirh_nombre',
        'tirh_valor',
        'tirh_creado',
        'tirh_actualizado',
        'tirh_vigente',
        'tirh_rut_mod',
        'tirh_rol_mod'
    ];
}
