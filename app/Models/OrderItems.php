<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Orders;  
use App\Models\Products_Variants;
use App\Models\Products;
class OrderItems extends Model

{
    use HasFactory;

    protected $fillable = [
        'id_order',
        'id_variants',
        'id_product',
        'title',
        'price',
        'quantity',
    ];

    /**
     * Item pertence a um pedido
     */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'id_order');
    }

    /**
     * Item pertence a uma variante de produto
     */
    public function variant()
    {
        return $this->belongsTo(Products_Variants::class, 'id_variants');
    }
    public function product()
{
       return $this->belongsTo(Products::class, 'id_product');
}

}