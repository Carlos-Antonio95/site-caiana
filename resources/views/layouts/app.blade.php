<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CAIANA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/styles.css', 'resources/js/app1.js'])
</head>
<body>

    {{-- Header incluído --}}
    @include('layouts.header')

    {{-- Conteúdo da página --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer incluído --}}
    @include('layouts.footer')
      <!-- Drawer do carrinho -->
 <aside class="drawer" id="drawer">
        <div class="drawer-head">
            <h3>Seu Carrinho</h3>
            <button class="btn-icon" id="close-drawer" aria-label="Fechar">✕</button>
        </div>
        <div class="drawer-body" id="cart-items"></div>
        <div class="drawer-foot">
            <div class="totals">
                <span>Subtotal</span>
                <strong id="subtotal">R$ 0,00</strong>
            </div>
            <button class="btn btn-primary" id="checkout">Finalizar Pedido</button>
            <p class="secure">🔒 Compra segura e confiável</p>
        </div>
    </aside>

    <div class="backdrop" id="backdrop"></div>

  <script src="{{ asset('js/cart.js') }}"></script>

    <script src="{{ asset('js/app1.js') }}"></script>
</body>
</html>
