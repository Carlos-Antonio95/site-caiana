<!--<header class="site-header">-->
<style>
html, body { overflow-x: hidden; }

.site-header {
    position: sticky;
    top: 0;
    z-index: 50;
    background: rgba(217, 245, 255, 0.9);
    backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(0,0,0,.05);
    width: 100%;
    padding: 0.5rem 2rem;

    display: flex;
}

.header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: .6rem 1rem;
    flex-wrap: wrap;
    transition: all 0.2s;
    left: 100px;
}

.brand {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-weight: 800;
    letter-spacing: .08em;
}

.logo-text { font-size: 1.4rem; }
.logo-link { text-decoration:none; color:inherit; cursor:pointer; }
.logo-link:hover { opacity:0.8; }

.main-nav {
    display: flex;
    gap: 1rem;
}

.main-nav a {
    color: #111;
    text-decoration: none;
    font-weight: 600;
    opacity: .85;
    transition: opacity .2s;
}
.main-nav a:hover { opacity:1; }

.actions {
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-left: auto;
    padding-right: 1rem;
}

.actions input[type="search"] {
    padding: .4rem .6rem;
    border-radius: .4rem;
    border: 1px solid #ccc;
}

.mobile-extra {
    display: none;
    width: 100%;
    padding: .2rem .4rem .4rem;
    background: rgba(217, 245, 255, 0.9);
    border-top: 1px solid rgba(0, 0, 0, .05);
    text-align: center;
}

.mobile-extra input {
    max-width: 320px;
    width: 100%;
    padding: .4rem .5rem;
    border: 1px solid #ccc;
    border-radius: .4rem;
    margin-bottom: .4rem;
}

.mobile-auth {
    display: flex;
    justify-content: center;
    gap: .5rem;
    flex-wrap: wrap;
}

.mobile-auth a {
    text-decoration: none;
    font-weight: 600;
    color: #3a258a;
}

#menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.6rem;
    cursor: pointer;
    margin-left: auto;
}

/* Menu mobile */
@media (max-width: 768px) {
    .header-inner { padding: .3rem .4rem; gap: .2rem; }
    .brand .logo-text { font-size: 1.2rem; }
    .actions input[type="search"] { display: none; }
    .actions .btn-link { display: none; }

    .main-nav {
        display: none;
        flex-direction: column;
        background: #ffffff;
        position: absolute;
        top: 50px;
        right: 10px;
        padding: .8rem 1rem;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        width: 220px;
        z-index: 100;
    }

    .main-nav.open { display: flex; }

    .main-nav hr { border:none; border-top:1px solid #eee; margin:.6rem 0; }

    .main-nav .user-section {
        display: flex;
        flex-direction: column;
        gap: .4rem;
        font-size: .85rem;
        color: #444;
    }

    .main-nav .user-section a,
    .main-nav .user-section button { font-size: .9rem; }

    .mobile-extra { display: block; }
    #menu-toggle { display:block; }
}

</style>

<div class="container header-inner">
    <div class="brand">
       <a href="{{ route('index') }}" class="logo-link">
           <span class="logo-text">CAIANA</span>
           <span class="logo-parrot" aria-hidden="true">🦜</span>
       </a>
    </div>

    <nav class="main-nav">
        <a href="{{ route('index') }}" class="@yield('nav-index')">🏠 Início</a>
        <a href="{{ route('produtos') }}" class="@yield('nav-produtos')">🛒 Produtos</a>
        <a href="{{ route('promocoes') }}" class="@yield('nav-promocoes')">💸 Promoções</a>
        <a href="{{ route('cart.show') }}" class="@yield('nav-cart')">🛍️ Carrinho</a>
        <a href="{{ route('meus.pedidos') }}" class="@yield('nav-pedidos')">📦 Pedidos</a>
        <a href="{{ route('contato') }}" class="@yield('nav-contato')">📞 Contato</a>
        @auth
            <hr>
            <div class="user-section">
                <span>👋 Olá, {{ Auth::user()->name }}!</span>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard') }}">👤 Painel</a>
                @endif
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">🚪 Sair</button>
                </form>
            </div>
        @endauth
    </nav>
    
    <div class="actions">
        <input type="search" id="search" placeholder="Buscar…" />
        <button id="btn-cart" class="btn btn-dark" aria-label="Abrir carrinho">🛍️ <span id="cart-count">0</span></button>
        <button id="menu-toggle" aria-label="Abrir menu">☰</button>

        @guest
            <a href="{{ route('login') }}" class="btn btn-link no-underline">Entrar</a>
            <a href="{{ route('register') }}" class="btn btn-link no-underline">Cadastrar</a>
        @endguest
    </div>
</div>

@guest
<div class="mobile-extra">
    <div class="mobile-auth">
        <a href="{{ route('login') }}">Entrar</a>
        <a href="{{ route('register') }}">Cadastrar</a>
    </div>
</div>
@endguest

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('menu-toggle');
    const nav = document.querySelector('.main-nav');
    const links = nav.querySelectorAll('a');

    // Abrir/fechar menu
    toggle.addEventListener('click', () => nav.classList.toggle('open'));

    // Fechar menu ao clicar em link
    links.forEach(link => link.addEventListener('click', () => nav.classList.remove('open')));

    // Fechar ao clicar fora
    document.addEventListener('click', (e) => {
        if(nav.classList.contains('open') && !nav.contains(e.target) && e.target !== toggle){
            nav.classList.remove('open');
        }
    });
});
</script>
<!--</header>-->
