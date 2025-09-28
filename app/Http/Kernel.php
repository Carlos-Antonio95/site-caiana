<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Pilha global de middleware HTTP da aplicação.
     * Esses middleware são executados em **todas as requisições**.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class, // Middleware para confiar em hosts específicos
        // \App\Http\Middleware\TrustProxies::class, // Lida com proxies confiáveis
        \Illuminate\Http\Middleware\HandleCors::class, // Gerencia requisições CORS
        // \App\Http\Middleware\PreventRequestsDuringMaintenance::class, // Bloqueia requisições quando a aplicação está em manutenção
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // Valida tamanho máximo de POST
        // \App\Http\Middleware\TrimStrings::class, // Remove espaços no início e fim de strings
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // Converte strings vazias para null
    ];

    /**
     * Grupos de middleware de rotas da aplicação.
     * Permitem aplicar vários middleware juntos a grupos de rotas.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [ // Middleware aplicados a rotas web (sessions, cookies, CSRF)
            // \App\Http\Middleware\EncryptCookies::class, // Criptografa cookies
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // Adiciona cookies pendentes à resposta
            \Illuminate\Session\Middleware\StartSession::class, // Inicia sessão
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Compartilha erros da sessão com views
            \App\Http\Middleware\VerifyCsrfToken::class, // Protege contra CSRF
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Substitui bindings de rota
        ],

        'api' => [ // Middleware aplicados a rotas de API
            'throttle:api', // Limita requisições por minuto
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Substitui bindings de rota
        ],
    ];

    /**
     * Middleware de rota individual.
     * Permite aplicar middleware específicos a rotas usando ->middleware('nome').
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class, // Verifica se o usuário está logado
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // Autenticação HTTP básica
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class, // Define cabeçalhos de cache
        'can' => \Illuminate\Auth\Middleware\Authorize::class, // Verifica autorização de ação
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Redireciona se usuário já estiver logado
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // Exige confirmação de senha
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class, // Verifica se rota possui assinatura válida
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // Limita requisições por IP
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Verifica se o email do usuário está confirmado
        'csrf' => \App\Http\Middleware\VerifyCsrfToken::class, // Protege contra CSRF
        'admin' => \App\Http\Middleware\AdminMiddleware::class, // Middleware customizado para verificar se o usuário é admin
    ];
}
