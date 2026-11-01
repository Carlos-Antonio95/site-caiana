<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products_Images extends Model
{
 use HasFactory;

    protected $fillable = [
        'id_products',
        'image_path',
        'is_primary',
    ];

    /**
     * Relação com o produto
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'id_products');
    }

    /** Regras de upload: tipos aceitos e tamanho máximo (KB). */
    public const UPLOAD_RULES = 'image|mimes:jpg,jpeg,png,webp|max:5120';

    /** Formato aceito para image_path: arquivo direto em public/assets. */
    public const PATH_REGEX = '/^assets\/[A-Za-z0-9_-]+\.(jpe?g|png|webp|gif)$/';

    /**
     * Salva o upload em public/assets com nome aleatório e extensão
     * deduzida do conteúdo (nunca a enviada pelo cliente).
     * Retorna o caminho relativo gravado no banco ("assets/xxx.jpg").
     */
    public static function storeUpload(\Illuminate\Http\UploadedFile $file): string
    {
        $fileName = $file->hashName();
        $file->move(public_path('assets'), $fileName);

        return 'assets/' . $fileName;
    }

    /**
     * Apaga o arquivo de um image_path, só se ele estiver em public/assets.
     */
    public static function deleteFile(?string $imagePath): void
    {
        if (! $imagePath || ! preg_match(self::PATH_REGEX, $imagePath)) {
            return;
        }

        $file = public_path($imagePath);
        if (is_file($file)) {
            @unlink($file);
        }
    }
}
