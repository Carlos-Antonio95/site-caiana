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
