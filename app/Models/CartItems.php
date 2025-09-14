<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CartItems extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'id_carts',
        'id_products',
        'quantity',
        'price',
    ];

    /**
     * Relacionamento: item pertence a um carrinho.
     */
    public function cart()
    {
        return $this->belongsTo(Carts::class, 'id_carts');
    }

    /**
     * Relacionamento: item pertence a um produto.
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'id_products');
    }
}
