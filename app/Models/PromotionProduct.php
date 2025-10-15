<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class PromotionProduct extends Model
{
use HasFactory;

    protected $fillable = [
        'id_promotions',
        'id_products',
        'percentage_discount',
        'promotional_price',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotions::class, 'id_promotions');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'id_products');
    }
}