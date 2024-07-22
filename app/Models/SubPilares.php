<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPilares extends Model {
    use HasFactory;

    protected $table = "sub_pilares";

    public $timestamps = false;

    protected $fillable = [
        'subpr_nombre'
    ];
}
