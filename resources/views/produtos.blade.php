@extends('layouts.app')

@section('title', 'CAIANA — Produtos')

@section('nav-index', 'is-active') <!-- Aba ativa -->

@section('content')
<main id="produtos">
  
  <!-- CATÁLOGO -->
      

    <section class="container" style="padding-top:2rem;">
      <h2>Todos os Produtos</h2>
      <div class="products">
        <!-- Exemplo de produto -->
      </div>
    </section>
 
 

  <script>
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
   </main>
@endsection