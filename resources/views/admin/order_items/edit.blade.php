@extends('layouts.admin')

@section('title', 'Editar Item')

@section('header')
    Editar Item do Pedido #{{ $orderItem->id_order }}
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('order_items.update', $orderItem) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Produto / Nome</label>
            <input type="text" name="product_name" class="form-control" value="{{ $orderItem->product_name }}" required>

            <label>Variante</label>
            <select name="id_variants" class="form-control">
                <option value="">Nenhuma</option>
                @foreach($variants as $variant)
                    <option value="{{ $variant->id }}" {{ $orderItem->id_variants == $variant->id ? 'selected' : '' }}>
                        {{ $variant->name }}
                    </option>
                @endforeach
            </select>

            <label>Quantidade</label>
            <input type="number" name="quantity" class="form-control" value="{{ $orderItem->quantity }}" min="1" required>

            <label>Preço Unitário</label>
            <input type="number" name="price" class="form-control" value="{{ $orderItem->price }}" min="0" step="0.01" required>

            <button type="submit" class="btn btn-success mt-2">Atualizar</button>
            <a href="{{ route('orders.show', $orderItem->id_order) }}" class="btn btn-secondary mt-2">Voltar</a>
        </form>
    </div>
</div>
@endsection
