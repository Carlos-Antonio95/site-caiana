@extends('layouts.admin')

@section('title', 'Detalhes da Promoção de Produto')

@section('header')
    Detalhes da Promoção Aplicada
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>{{ $promotionProduct->promotion->name ?? '-' }}</h2>
        <p><strong>Produto:</strong> {{ $promotionProduct->product->title ?? '-' }}</p>
        <p><strong>Desconto (%):</strong> {{ $promotionProduct->percentage_discount ?? '-' }}</p>
        <p><strong>Preço Promocional:</strong> {{ $promotionProduct->promotional_price ?? '-' }}</p>

        <a href="{{ route('promotion_products.index') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('promotion_products.edit', $promotionProduct) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('promotion_products.destroy', $promotionProduct) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('Remover promoção do produto?')">Excluir</button>
        </form>
    </div>
</div>
@endsection
