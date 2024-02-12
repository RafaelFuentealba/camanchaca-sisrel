<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisiones extends Model
{
    use HasFactory;
    protected $table = 'divisiones';

    public $timestamps = false;

    protected $fillable = [
        // primary key
        'divi_codigo',
        // campos
        'divi_nombre',
        'divi_creado',
        'divi_actualizado',
        'divi_vigente'
    ];
}
