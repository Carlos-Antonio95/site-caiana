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

    protected $casts = [
        'expiration_date' => 'date',
        'active' => 'boolean',
    ];

    // Código sempre em maiúsculas (no PostgreSQL a comparação diferencia caixa)
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = mb_strtoupper(trim((string) $value));
    }

    public static function findByCode(?string $code): ?self
    {
        return static::where('code', mb_strtoupper(trim((string) $code)))->first();
    }

    // Ativo, com usos disponíveis e dentro da validade (vale até o fim do dia)
    public function isActive()
    {
        return $this->active
            && $this->max_use > 0
            && $this->expiration_date
            && now()->startOfDay()->lte($this->expiration_date);
    }

    /**
     * Motivo pelo qual o cupom não pode ser usado neste subtotal, ou null.
     */
    public function rejectionReason(float $subtotal): ?string
    {
        if (! $this->active || ! $this->expiration_date || now()->startOfDay()->gt($this->expiration_date)) {
            return 'Cupom expirado ou inativo.';
        }
        if ($this->max_use <= 0) {
            return 'Este cupom atingiu o limite de usos.';
        }
        if ($subtotal < $this->min_discount) {
            return 'O valor mínimo para usar este cupom é R$ ' . number_format($this->min_discount, 2, ',', '.');
        }

        return null;
    }

    // Valor do desconto para o subtotal (nunca maior que o subtotal)
    public function discountFor(float $subtotal): float
    {
        $discount = $this->discount_type === 'percentual'
            ? $subtotal * ($this->discount_value / 100)
            : (float) $this->discount_value;

        return round(min($discount, $subtotal), 2);
    }
}
