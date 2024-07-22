<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoInfraestructura extends Model {
    use HasFactory;

    protected $table = "tipo_infraestructura";

    public $timestamps = false;

    protected $fillable = [
        'tiin_nombre',
        'tiin_valor',
        'tiin_creado',
        'tiin_actualizado',
        'tiin_vigente',
        'tiin_rut_mod',
        'tiin_rol_mod'
    ];
}
