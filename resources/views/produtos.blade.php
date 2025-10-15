@extends('layouts.app')

@section('title', 'CAIANA — Produtos')

@section('nav-produtos', 'is-active') <!-- Aba ativa -->

@section('content')
<main id="produtos">
    <section class="catalog container">
        <!-- FILTROS LATERAIS -->
        <aside class="filters">
            <h3>Filtros</h3>

            <div class="filter-block">
                <label class="lbl">Categoria</label>
                <div class="chips" id="chip-categorias">
                    <button class="chip is-active" data-category="tudo">Tudo</button>
                    @foreach($categories as $category)
                        <button class="chip" data-category="{{ strtolower($category->category_name) }}">
                            {{ $category->category_name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="filter-block">
                <label class="lbl">Preço (R$)</label>
                <input id="preco" type="range" min="50" max="500" value="500" />
                <div class="range-legend">
                    <span>Mín</span>
                    <span id="preco-val">Até 500</span>
                </div>
            </div>
        </aside>

        <!-- PRODUTOS -->
        <div class="products">
            <!-- Cards de produtos serão populados via JS -->
        </div>
    </section>

    <!-- CHECKOUT DRAWER (igual da home) -->
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

<!-- Script para mostrar valor do range e filtrar -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const precoInput = document.getElementById('preco');
    const precoVal = document.getElementById('preco-val');

    precoInput.addEventListener('input', () => {
        precoVal.textContent = `Até ${precoInput.value}`;
        // Aqui você pode chamar a função JS para filtrar produtos pelo preço
        // ex: filterProducts({ maxPrice: precoInput.value });
    });

    // Filtragem por categorias
    const chips = document.querySelectorAll('#chip-categorias .chip');
    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('is-active'));
            chip.classList.add('is-active');

            const category = chip.dataset.category;
            // Aqui você pode chamar sua função JS para filtrar produtos
            // ex: filterProducts({ category });
        });
    });
});
</script>
@endsection
