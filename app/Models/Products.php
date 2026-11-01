<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Categories;
use App\Models\PromotionProduct;
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

    // Validação dos itens do carrinho enviados pelo front ([{id, qty}, ...])
    public const CART_RULES = [
        'items'        => 'required|array|min:1|max:50',
        'items.*.id'   => 'required|integer|exists:products,id',
        'items.*.qty'  => 'required|integer|min:1|max:99',
    ];

    /**
     * Subtotal do carrinho com os preços do banco (produtos ativos apenas).
     */
    public static function subtotalFor(array $items): float
    {
        $products = static::whereIn('id', array_column($items, 'id'))
            ->where('status', 'ativo')
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        foreach ($items as $item) {
            if ($product = $products->get($item['id'])) {
                $subtotal += $product->final_price * $item['qty'];
            }
        }

        return round($subtotal, 2);
    }

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


public function promotionProduct()
{
    return $this->hasOne(PromotionProduct::class, 'id_products');
}
}
