@extends('layouts.app')

@section('title', 'CAIANA — Moda Tropical')

@section('nav-index', 'is-active') <!-- Aba ativa -->

@section('content')
<main id="home">
    <!-- HERO -->
    <section class="hero container">
        <div class="hero-text">
            <h1>Viva o <span>Tropical</span></h1>
            <p>Estampas vibrantes, conforto e estilo para todas as ocasiões.</p>
            <a href="#catalogo" class="btn btn-dark">Ver Produtos</a>
        </div>
        <div class="hero-art">
            <img src="assets\rosa.jpg" alt="Coleção tropical CAIANA" />
        </div>
    </section>

    <!-- CATÁLOGO -->
    <section id="catalogo" class="catalog container">
        <aside class="filters">
            <h3>Filtros</h3>
            <div class="filter-block">
                <label class="lbl">Categoria</label>
                <div class="chips" id="chip-categorias">
                    <button class="chip is-active" data-category="tudo">Tudo</button>
                    <button class="chip" data-category="vestidos">Vestidos</button>
                    <button class="chip" data-category="saias">Saias</button>
                    <button class="chip" data-category="shorts">Shorts</button>
                    <button class="chip" data-category="blusas">Blusas</button>
                </div>
            </div>

            <div class="filter-block">
                <label class="lbl">Preço (R$)</label>
                <input id="preco" type="range" min="50" max="500" value="500" />
                <div class="range-legend"><span>Mín</span><span id="preco-val">Até 500</span></div>
            </div>
        </aside>


      <div class="products">
    <!-- Cards são populados via JS -->
        </div>

    </section>

    <!-- CHECKOUT DRAWER -->
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
</main>
@endsection
