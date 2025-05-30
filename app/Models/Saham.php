<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saham extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'nama_saham',
        'open',
        'high',
        'low',
        'close',
        'volume',

    ];
}
