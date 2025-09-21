@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Carrinho</h1>

    <div class="cart">
        <h2>Seu Carrinho</h2>
        <div id="cart-items"></div>

        <div class="subtotal">
            Total: <span id="subtotal">R$0,00</span>
        </div>

        <button id="checkout" class="btn btn-primary">Finalizar Pedido</button>
    </div>
</div>

<!-- CSRF Token obrigatório para POST Laravel -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- JS de produtos e carrinho -->
@vite(['resources/js/app.js', 'resources/js/cart.js'])
@endsection
