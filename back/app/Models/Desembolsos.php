<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desembolsos extends Model
{
    use HasFactory;
    protected $fillable = ['fecha_desembolso', 'monto_desembolso', 'responsable', 'candidato_id'];
}
