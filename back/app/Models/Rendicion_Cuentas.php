<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendicion_Cuentas extends Model
{
    use HasFactory;
    protected $fillable = ['fecha_rendicion', 'monto_rendicion', 'desembolso_id'];
}
