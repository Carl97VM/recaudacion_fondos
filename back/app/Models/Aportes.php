<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aportes extends Model
{
    use HasFactory;
    protected $fillable = ['tipo_aporte', 'fecha_inicio_aporte', 'fecha_fin_aporte', 'descripcion', 'archivo', 'usuario_id', 'aportantes_id'];
    public function candidatos()
    {
        return $this->belongsToMany(Candidatos::class);
    }
}
