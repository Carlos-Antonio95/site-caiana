<header class="site-header">
    <div class="container header-inner">
        <div class="brand">
            <span class="logo-text">CAIANA</span>
            <span class="logo-parrot" aria-hidden="true">🦜</span>
        </div>

        <nav class="main-nav">
            <a href="{{ route('index') }}" class="@yield('nav-index')">Início</a>
            <a href="{{ route('produtos') }}" class="@yield('nav-produtos')">Produtos</a>
            <a href="{{ route('promocoes') }}" class="@yield('nav-promocoes')">Promoções</a>
            <a href="{{ route('contato') }}" class="@yield('nav-contato')">Contato</a>
        </nav>

        <div class="actions">
            <input type="search" id="search" placeholder="Buscar…" />
            <button id="btn-cart" class="btn btn-dark" aria-label="Abrir carrinho">
                🛍️ <span id="cart-count">{{ session('cart.count', 0) }}</span>
            </button>

            @auth
                <span class="user-greeting">Olá, {{ Auth::user()->name }}!</span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-link">Sair</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-link no-underline">Entrar</a>
                <a href="{{ route('register') }}" class="btn btn-link no-underline">Cadastrar</a>

            @endauth
        </div>
    </div>
</header>
