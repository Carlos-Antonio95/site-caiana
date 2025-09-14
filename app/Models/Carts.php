<?php

namespace App\Models;
use App\Models\Cart_Items;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Carts extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_clients',
        'token_session',
    ];

    /**
     * Relacionamento: um carrinho pertence a um cliente (opcional).
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_clients');
    }

    /**
     * Relacionamento: um carrinho possui vários itens.
     */
    public function items()
    {
        return $this->hasMany(CartItems::class, 'id_carts');
    }
}
