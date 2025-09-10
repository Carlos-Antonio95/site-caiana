<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;
    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'id_clients',
        'addresses_name',
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
     * Relacionamento: Endereço pertence a um cliente
     */
    public function client(){
        return $this->belongsTo(Client::class, 'id_clients');
    }
    
}
