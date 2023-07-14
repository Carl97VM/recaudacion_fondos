<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidatos extends Model
{
    use HasFactory;
    protected $fillable = ['ci','nombres','apellidos','celular','nombre_candidatura'];
    public function aportes()
    {
        return $this->belongsToMany(Aportes::class);
    }
}
