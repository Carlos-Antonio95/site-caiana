@extends('layouts.app')

@section('title', 'CAIANA — Promocoes')

@section('nav-index', 'is-active') <!-- Aba ativa -->

@section('content')
<main id="promocoes">
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


  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>

  document.getElementById('year').textContent = new Date().getFullYear();
  </script>
   </main>
@endsection