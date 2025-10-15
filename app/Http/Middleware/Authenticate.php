<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Intercepta a requisição e verifica se o usuário está logado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Se não estiver logado, redireciona para login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Se estiver logado, segue para a próxima etapa da requisição
        return $next($request);
    }
}
