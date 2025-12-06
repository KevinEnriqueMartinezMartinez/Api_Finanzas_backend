<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

        protected $fillable = [
        'cliente_id',
        'monto',
        'descripcion',
        'fecha',
        'mes_id',
        'categoria_id'
    ];

     // Relación con la tabla clientes
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

}
