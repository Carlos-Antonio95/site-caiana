<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReviews extends Model
{
   use HasFactory;

    protected $fillable = [
        'id_products',
        'id_clients',
        'rating',
        'comments',
        'status',
    ];

    // Relacionamento com produto
    public function product()
    {
        return $this->belongsTo(Products::class, 'id_products');
    }

    // Relacionamento com cliente
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_clients');
    }
}