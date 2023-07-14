<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalles extends Model
{
    use HasFactory;
    protected $fillable = ['descripcion', 'monto_detalle', 'rendicion_cuentas_id'];
}
