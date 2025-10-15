<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_Variants extends Model
  {
    use HasFactory;

    protected $fillable = [
        'id_products',
        'size',
        'color',
        'additional_price',
        'stock_quantity',

    ];
     protected $table = 'product_variants'; // Nome correto da tabela no banco

    /**
     * Relação com o produto principal
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'id_products');
    }
}