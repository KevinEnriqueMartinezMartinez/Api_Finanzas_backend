<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

      protected $fillable = [
        'cliente_id',
        'monto',
        'descripcion',
        'fecha',
        'mes_id'
    ];

     // Relación con la tabla clientes
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
