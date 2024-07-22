<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilarSubPilar extends Model {
    use HasFactory;

    protected $table = "pilar_subpilar";

    public $timestamps = false;

    protected $fillable = [
        'pila_codigo',
        'subpr_codigo'
    ];
}
