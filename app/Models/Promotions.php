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
}