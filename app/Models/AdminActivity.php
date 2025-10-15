<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AdminActivity extends Model
{
      use HasFactory;

    protected $table = 'admin_activities';

    protected $fillable = [
        'id_admins',
        'activity',
        'ip_address',
    ];

    /**
     * Relacionamento com o admin (usuário)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admins');
    }
}