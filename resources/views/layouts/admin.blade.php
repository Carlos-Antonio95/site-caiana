<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Painel CAIANA')</title>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">


    <style>
        body {
            display:flex;
            min-height:100vh;
            font-family: 'Poppins', sans-serif;
            margin:0;
        }

        /* Sidebar fixa no desktop */
        .sidebar {
            width:250px;
            background:#365366;
            color:#fff;
            display:flex;
            flex-direction:column;
            position:relative;
        }

        .sidebar h2 {
            text-align:center;
            padding:1rem;
            border-bottom:1px solid #34495e;
            font-size:1.2rem;
        }

        .sidebar a {
            padding:0.75rem 1rem;
            color:#fff;
            text-decoration:none;
            transition:0.2s;
        }

        .sidebar a:hover, .sidebar a.active {
            background:#34495e;
        }

        .content {
            flex:1;
            padding:2rem;
            background:#ecf0f1;
        }

        /* Hamburger mobile */
        .hamburger {
            display:none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            font-size: 1.5rem;
            background:#365366;
            color:#fff;
            border:none;
            padding:0.5rem 0.75rem;
            cursor:pointer;
            z-index:1000;
            border-radius:5px;
        }

        /* Mobile */
        @media (max-width:768px){
            body { flex-direction:column; }
            .sidebar { position:fixed; left:-250px; top:0; height:100%; z-index:999; transition:0.3s; }
            .sidebar.active { left:0; }
            .hamburger { display:block; }
            .content { padding:1rem; margin-left:0; }
        }

        /* Botões padrão */
        .btn {
            display:inline-block;
            padding:10px 18px;
            border-radius:10px;
            font-size:.95rem;
            font-weight:600;
            cursor:pointer;
            text-decoration:none;
            transition:all .2s ease-in-out;
            border:none;
        }
        .btn-primary { background:darkblue; color:#fff; }
        .btn-warning { background:orange; color:#fff; }
        .btn-danger { background:red; color:#fff; }
        .btn-info { background:black; color:#fff; }
        .btn-dark { background:#222; color:#fff; }
        .btn:hover { opacity:0.9; }
  
}
    </style>
</head>
<body>
    <button class="hamburger" onclick="toggleSidebar()">☰</button>

    <aside class="sidebar">
        <h2>CAIANA Admin</h2>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Usuários</a>
        <a href="{{  route('admin.carts.index') }}" class="{{ request()->routeIs('admin.carts.*') ? 'active' : '' }}">Carrinhos</a>
        <a href="{{  route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categorias</a>
        <a href="{{  route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">Clientes</a>
        <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">Contatos</a>
        <a href="{{ route('admin.addresses.index') }}" class="{{ request()->routeIs('admin.addresses.*') ? 'active' : '' }}">Endereços</a>
        <a href="{{ route('admin.products_images.index') }}" class="{{ request()->routeIs('admin.products_images.*') ? 'active' : '' }}">Imagens dos Produtos</a>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Produtos</a>
        <a href="{{ route('admin.promotion_products.index') }}" class="{{ request()->routeIs('admin.promotion_products.*') ? 'active' : '' }}">Produtos na promoção</a>
        <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">Promoções</a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Pedidos</a>
        <a href="{{  route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">Cupons</a>

        <form action="{{ route('logout') }}" method="POST" style="margin-top:auto; padding:1rem;">
            @csrf
            <a href="{{ route('index') }}" class="btn btn-dark w-100 mb-2">Voltar ao site</a>
            <button type="submit" class="btn btn-danger w-100">Sair</button>
        </form>
    </aside>

    <main class="content">
        <h1>@yield('header')</h1>
        @yield('content')
    </main>

  <script>
    const sidebar = document.querySelector('.sidebar');

    function toggleSidebar() {
        if(window.innerWidth <= 768){
            sidebar.classList.toggle('active');
        }
    }

    // Fecha sidebar ao clicar fora
    document.addEventListener('click', function(e) {
        if(window.innerWidth <= 768 && sidebar.classList.contains('active')) {
            const isClickInside = sidebar.contains(e.target) || e.target.classList.contains('hamburger');
            if(!isClickInside) {
                sidebar.classList.remove('active');
            }
        }
    });
</script>
</body>
</html>
