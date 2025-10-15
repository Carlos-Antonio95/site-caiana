<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Promotions extends Model
{
  use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'banner',
        'active',
    ];

    public function scopeValid($query)
    {
        return $query->where('active', true)
                     ->whereDate('start_date', '<=', now())
                     ->whereDate('end_date', '>=', now());
    }

    public function products()
    {
        return $this->hasMany(PromotionProduct::class, 'id_promotions');
    }

}