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
        'title',
        'description',
        'price',
        'stock_quantity',
        'status',
    ];

    // Preço final do produto (considerando promoções)
   public function getFinalPriceAttribute()
{
    // Se tiver desconto em porcentagem
    $promotionProduct = PromotionProduct::with('promotion')
        ->where('id_products', $this->id)
        ->whereHas('promotion', function($q) {
            $q->valid();
        })
        ->first();

        // Se tiver preço promocional fixo
    if ($promotionProduct) {
        if ($promotionProduct->percentage_discount) {
            return round($this->price * (1 - $promotionProduct->percentage_discount / 100), 2);
        }
        if ($promotionProduct->promotional_price) {
            return $promotionProduct->promotional_price;
        }
    }

    return $this->price; // preço normal se não houver promoção
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
