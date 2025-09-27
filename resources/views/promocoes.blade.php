@extends('layouts.app')

@section('title', 'CAIANA — Promocoes')

@section('nav-index', 'is-active') <!-- Aba ativa -->

@section('content')
<main id="promocoes">
    <section class="container" style="padding-top:2rem;">
      <h2>Itens em Promoção</h2>
      <div class="products">
        <!-- Exemplo de produto -->
      </div>
    </section>


  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script> 
   </main>
@endsection