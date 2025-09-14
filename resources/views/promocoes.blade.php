<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Promoções — CAIANA</title>
  @vite(['resources/css/styles.css', 'resources/js/app1.js'])
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <div class="brand">
        <span class="logo-text">CAIANA</span>
        <span class="logo-parrot" aria-hidden="true">🦜</span>
      </div>
      <nav class="main-nav">
        <a href="{{ route('index') }}">Início</a>
        <a href="{{ route('produtos') }}">Produtos</a>
        <a href="{{ route('promocoes') }}" class="is-active">Promoções</a>
        <a href="{{ route('contato') }}">Contato</a>
      </nav>
      <div class="actions">
        <input type="search" id="search" placeholder="Buscar…" />
        <button id="btn-cart" class="btn btn-dark" aria-label="Abrir carrinho">🛍️ <span id="cart-count">0</span></button>
      </div>
    </div>
  </header>

  <main>
    <section class="container" style="padding-top:2rem;">
      <h2>Itens em Promoção</h2>
      <div class="products">
        <div class="card">
          <img src="assets/Vestido.jpg" alt="Vestido Tropical" />
          <h4>Vestido Tropical</h4>
          <span class="price"><del>R$ 199,90</del> <strong>R$ 149,90</strong></span>
          <button 
            class="btn btn-primary add-cart"
            data-name="Vestido Tropical"
            data-price="149.90"
            data-img="assets/Vestido.jpg"
          >Adicionar ao Carrinho</button>
        </div>
        <div class="card">
          <img src="assets/Saia.jpg" alt="Saia Estampada" />
          <h4>Saia Estampada</h4>
          <span class="price"><del>R$ 129,90</del> <strong>R$ 99,90</strong></span>
          <button 
            class="btn btn-primary add-cart"
            data-name="Saia Estampada"
            data-price="99.90"
            data-img="assets/Saia.jpg"
          >Adicionar ao Carrinho</button>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <p>© <span id="year"></span> CAIANA • Moda Tropical</p>
      <div class="mini">
        <a href="#">Política de Privacidade</a>
        <a href="#">Termos</a>
      </div>
    </div>
  </footer>

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
</body>

</html>    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
</body>
</html>