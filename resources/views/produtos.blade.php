@extends('layouts.app')

@section('title', 'CAIANA — Produtos')

@section('nav-index', 'is-active') <!-- Aba ativa -->

@section('content')
<main id="produtos">
  
    <section class="container" style="padding-top:2rem;">
      <h2>Todos os Produtos</h2>
      <div class="products">
        <!-- Exemplo de produto -->
        <div class="card">
          <img src="assets\e7149891-7f4f-4168-98d6-2c27cf981baf.jpg" alt="Vestido Tropical" />
          <h4>Vestido Tropical</h4>
          <span class="price">R$ 199,90</span>
          <button 
            class="btn btn-primary add-cart"
            data-name="Vestido Tropical"
            data-price="199.90"
            data-img="assets/Vestido.jpg"
          >Adicionar ao Carrinho</button>
        </div>
        <div class="card">
          <img src="assets\e7149891-7f4f-4168-98d6-2c27cf981baf.jpg" alt="Saia Estampada" />
          <h4>Saia Estampada</h4>
          <span class="price">R$ 129,90</span>
          <button 
            class="btn btn-primary add-cart"
            data-name="Saia Estampada"
            data-price="129.90"
            data-img="assets/Saia.jpg"
          >Adicionar ao Carrinho</button>
        </div>
        <!-- Adicione mais produtos conforme necessário -->
      </div>
    </section>
 
 

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
   </main>
@endsection