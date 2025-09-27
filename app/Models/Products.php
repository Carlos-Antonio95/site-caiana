<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Categories;
use App\Models\Products_Images;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_categories',
        'product_name',
        'description',
        'price',
        'stock_quantity',
        'status',
    ];

    // Preço final do produto (considerando promoções)
    public function getFinalPriceAttribute()
    {
        // pega promoções válidas
        $promotion = PromotionProduct::with('promotion')
            ->where('id_products', $this->id)
            ->whereHas('promotion', function($q) {
                $q->valid();
            })
            ->first();

        if ($promotion) {
            if ($promotion->percentage_discount) {
                return $this->price * (1 - ($promotion->percentage_discount / 100));
            }
            if ($promotion->promotional_price) {
                return $promotion->promotional_price;
            }
        }

        return $this->price; // sem desconto
    }
    
    /**
     * Relação com categoria
     */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'id_categories');
    }
    public function images()
{
    return $this->hasMany(Products_Images::class, 'id_products');
}
}
