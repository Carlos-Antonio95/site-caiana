<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'CAIANA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/styles.css', 'resources/js/app1.js'])
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
.cart-item .meta strong,
.cart-item .meta small,
.cart-item .qty span {
  color: #000 !important;
}
</style>
   <style>
/* ====== Fundo da página com overlay azul ====== */
body {
    margin: 0;
    font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
    
    /* Camadas de fundo:
       1️⃣ Degradê azul semi-transparente
       2️⃣ Sua imagem de fundo (folhas) */
    background-image: 
   linear-gradient(
    rgba(49, 175, 192, 0.6),
    rgba(15, 72, 122, 0.7)
),
        url('{{ asset('assets/imgfundo.png') }}'); /* Caminho da imagem */
    
    background-size: cover;          /* cobre toda a tela */
    background-position: center;     /* centraliza a imagem */
    background-repeat: no-repeat;    /* não repete */
    background-attachment: fixed;    /* fixa ao rolar */
    background-color: #0f172a;       /* fallback (azul escuro sólido) */
    
    color: #fff;                     /* texto branco para contraste */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    line-height: 1.4;
    min-height: 100vh;
}
</style>

</head>
<body>
    <!-- ====== Aviso de Cookies ====== -->
<div id="cookie-banner" class="cookie-banner">
    <p>
        Usamos cookies para melhorar sua experiência em nosso site.
        Ao continuar navegando, você concorda com nossa 
        <a href="{{ route('politica') }}" class="cookie-link">Política de Privacidade</a>.
    </p>
    <div class="cookie-actions">
        <button id="accept-cookies" class="cookie-btn accept">Aceitar</button>
        <button id="decline-cookies" class="cookie-btn decline">Fechar</button>
    </div>
</div>

<style>
.cookie-banner {
    position: fixed;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 12px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    max-width: 600px;
    z-index: 10000;
    animation: slideUp 0.4s ease;
}

@keyframes slideUp {
    from { transform: translate(-50%, 100%); opacity: 0; }
    to { transform: translate(-50%, 0); opacity: 1; }
}

.cookie-banner p {
    margin: 0;
    flex: 1;
}

.cookie-actions {
    display: flex;
    gap: 8px;
}

.cookie-btn {
    border: none;
    padding: 6px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: 0.2s;
}

.cookie-btn.accept {
    background: #007bff;
    color: white;
}

.cookie-btn.accept:hover {
    background: #0056b3;
}

.cookie-btn.decline {
    background: #e0e0e0;
    color: #333;
}

.cookie-btn.decline:hover {
    background: #c5c5c5;
}
</style>
<style>
    #subtotal {
        color: black !important;
    }
    .totals{
         color: black !important;
    }
</style>
<style>
.products h4 {
  color: black !important;
}
.promotions h4 {
    color: black !important;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const banner = document.getElementById("cookie-banner");
    const acceptBtn = document.getElementById("accept-cookies");
    const declineBtn = document.getElementById("decline-cookies");

    // Exibe o banner se ainda não aceitou
    if (!localStorage.getItem("cookiesAceitos")) {
        banner.style.display = "flex";
    } else {
        banner.style.display = "none";
    }

    // Aceitar → grava no localStorage e esconde
    acceptBtn.addEventListener("click", function () {
        localStorage.setItem("cookiesAceitos", "true");
        banner.style.display = "none";
    });

    // Fechar → apenas esconde, sem gravar
    declineBtn.addEventListener("click", function () {
        banner.style.display = "none";
    });
});
</script>


    {{-- Faixa de demonstração (portfólio) --}}
    @if(config('loja.demo'))
        <div style="background:#1f2937; color:#f9fafb; font-size:.75rem; text-align:center; padding:.3rem 1rem; letter-spacing:.02em;">
            Loja demonstrativa — pagamento simulado
        </div>
    @endif

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
                <strong id="subtotal" style="color: black;">R$ 0,00</strong>
            </div>
            <button class="btn btn-primary" id="checkout">Finalizar Pedido</button>
            <p class="secure">🔒 Compra segura e confiável</p>
        </div>
    </aside>

    <div class="backdrop" id="backdrop"></div>

</body>
</html>
