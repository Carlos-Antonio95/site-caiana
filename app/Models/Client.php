<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'id_users',    // ID do usuário dono do cliente
        'full_name',
        'phone',
        'date_birth',
    ];

    /**
     * Relacionamento: Cliente pertence a um usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    /**
     * Se você quiser, pode adicionar casts para datas
     */
    protected $casts = [
        'date_birth' => 'date',
    ];
    
    public function addresses()
{
    return $this->hasMany(Addresses::class, 'id_clients'); // 'id_clients' é a FK no Addresses
}

}
