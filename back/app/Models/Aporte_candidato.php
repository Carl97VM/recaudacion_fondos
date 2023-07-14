<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aporte_candidato extends Model
{
    use HasFactory;
    protected $fillable = ['aporte_id','candidato_id','monto'];
}
