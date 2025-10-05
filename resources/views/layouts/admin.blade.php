<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Painel CAIANA')</title>
    @vite(['resources/css/admin.css', 'resources/js/app.js'])


    <style>
        body { display:flex; min-height:100vh; font-family: 'Poppins', sans-serif; margin:0; }
        .sidebar { width:250px; background:#365366; color:#fff; display:flex; flex-direction:column; }
        .sidebar h2 { text-align:center; padding:1rem; border-bottom:1px solid #34495e; }
        .sidebar a { padding:0.75rem 1rem; color:#fff; text-decoration:none; transition:0.2s; }
        .sidebar a:hover, .sidebar a.active { background:#34495e; }
        .content { flex:1; padding:2rem; background:#ecf0f1; }
    </style>
</head>
<body>
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
        <a href="{{ route('index') }}" class="auth-btn">
    Voltar ao site
</a>

       <button type="submit" class="auth-btn">
    Sair
</button>

    </form>
</aside>


    <main class="content">
        <h1>@yield('header')</h1>
        @yield('content')
    </main>
</body>
</html>
