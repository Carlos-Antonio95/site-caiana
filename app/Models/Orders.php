<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_clients',
        'id_addresses',
        'status',
        'total_value',
    ];

    /**
     * Relacionamento: Pedido pertence a um cliente
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_clients');
    }

    /**
     * Relacionamento: Pedido possui um endereço
     */
    public function address()
    {
        return $this->belongsTo(Addresses::class, 'id_addresses');
    }

    /**
     * Relacionamento: Pedido pode ter vários itens
     */
    public function items()
    {
        return $this->hasMany(OrderItems::class, 'id_orders');
    }
}