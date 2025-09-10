<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs que não precisam de CSRF.
     */
    protected $except = [
        // Adicione URLs que não precisam de CSRF, se houver
    ];
}
