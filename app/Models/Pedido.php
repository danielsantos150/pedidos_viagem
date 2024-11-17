<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'nome_solicitante',
        'destino',
        'data_ida',
        'data_volta',
        'status',
        'email'
    ];

    protected $casts = [
        'data_ida' => 'datetime',
        'data_volta' => 'datetime',
    ];

    public static function getValidStatus()
    {
        return ['aprovado', 'cancelado'];
    }

}

