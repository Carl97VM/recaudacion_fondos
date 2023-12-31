<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aportante extends Model
{
    use HasFactory;
    protected $fillable = ['ci', 'nombres', 'apellidos', 'celular', 'profesion_oficio', 'ciudad'];
}
