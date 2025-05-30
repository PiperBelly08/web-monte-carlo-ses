<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PorsiSaham extends Model
{
    use HasFactory;

     protected $fillable = [
        'date',
        'nama_saham',
        'close',
        'porsi',
     ];
}
