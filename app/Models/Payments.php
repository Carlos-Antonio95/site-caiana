<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Payments extends Model
{
  use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'id_orders',
        'method',
        'amount',
        'status',
    ];

    /**
     * Relacionamento: um pagamento pertence a um pedido
     */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'id_orders', 'id');
    }
}