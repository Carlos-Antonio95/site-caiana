@extends('layouts.admin')

@section('title', 'Adicionar Item')

@section('header')
    Adicionar Item ao Pedido #{{ request('order_id') }}
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('order_items.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_order" value="{{ request('order_id') }}">

            <label>Produto / Nome</label>
            <input type="text" name="product_name" class="form-control" required>

            <label>Variante</label>
            <select name="id_variants" class="form-control">
                <option value="">Nenhuma</option>
                @foreach($variants as $variant)
                    <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                @endforeach
            </select>

            <label>Quantidade</label>
            <input type="number" name="quantity" class="form-control" min="1" value="1" required>

            <label>Preço Unitário</label>
            <input type="number" name="price" class="form-control" min="0" step="0.01" required>

            <button type="submit" class="btn btn-success mt-2">Adicionar</button>
            <a href="{{ route('orders.show', request('order_id')) }}" class="btn btn-secondary mt-2">Voltar</a>
        </form>
    </div>
</div>
@endsection
