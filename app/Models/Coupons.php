<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupons extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_discount',
        'expiration_date',
        'max_use',
        'active',
    ];

    // Você pode criar funções auxiliares, por exemplo:
    public function isActive()
    {
        return $this->active && $this->expiration_date >= now();
    }
}