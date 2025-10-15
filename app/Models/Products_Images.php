<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products_Images extends Model
{
 use HasFactory;

    protected $fillable = [
        'id_products',
        'image_path',
        'is_primary',
    ];

    /**
     * Relação com o produto
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'id_products');
    }
    
}
