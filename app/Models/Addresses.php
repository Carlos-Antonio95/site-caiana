<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_clients',
        'road',
        'number',
        'complement',
        'referenc',
        'neighborhood',
        'city',
        'state',
        'cep',
        'country',
    ];

    /**
     * Relacionamento: endereço pertence a um cliente
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_clients');
    }
}
