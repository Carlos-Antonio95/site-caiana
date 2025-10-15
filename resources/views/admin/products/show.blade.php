@extends('layouts.admin')

@section('title', 'Detalhes do Produto')

@section('header')
    Detalhes do Produto
@endsection

@section('content')
<div class="container">
    <div class="card">
        <p><strong>Título:</strong> {{ $product->title }}</p>
        <p><strong>Categoria:</strong> {{ $product->category->category_name ?? '-' }}</p>
        <p><strong>Descrição:</strong> {{ $product->description }}</p>
        <p><strong>Preço:</strong> R$ {{ number_format($product->price, 2, ',', '.') }}</p>
        <p><strong>Quantidade em Estoque:</strong> {{ $product->stock_quantity }}</p>
        <p><strong>Status:</strong> {{ ucfirst($product->status) }}</p>

        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Editar</a>
    </div>
</div>
@endsection
