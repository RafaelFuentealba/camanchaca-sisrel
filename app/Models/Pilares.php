<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilares extends Model {
    use HasFactory;

    protected $table = "pilares";

    public $timestamps = false;

    protected $fillable = [
        'pila_nombre',
        'pila_creado',
        'pila_actualizado',
        'pila_vigente',
        'pila_rut_mod',
        'pila_rol_mod'
    ];
}
