@extends('layouts.admin')

@section('title', 'Editar Promoção de Produto')

@section('header')
    Editar Promoção de Produto
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('promotion_products.update', $promotionProduct) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Promoção</label>
            <select name="id_promotions" class="form-control" required>
                <option value="">Selecione uma promoção</option>
                @foreach($promotions as $promotion)
                    <option value="{{ $promotion->id }}" {{ old('id_promotions', $promotionProduct->id_promotions) == $promotion->id ? 'selected' : '' }}>
                        {{ $promotion->name }}
                    </option>
                @endforeach
            </select>

            <label>Produto</label>
            <select name="id_products" class="form-control" required>
                <option value="">Selecione um produto</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('id_products', $promotionProduct->id_products) == $product->id ? 'selected' : '' }}>
                        {{ $product->title }}
                    </option>
                @endforeach
            </select>

            <label>Desconto (%)</label>
            <input type="number" name="percentage_discount" class="form-control" value="{{ old('percentage_discount', $promotionProduct->percentage_discount) }}" min="0" max="100">

            <label>Preço Promocional</label>
            <input type="number" step="0.01" name="promotional_price" class="form-control" value="{{ old('promotional_price', $promotionProduct->promotional_price) }}" min="0">

            <button type="submit" class="btn btn-success mt-3">Atualizar</button>
            <a href="{{ route('promotion_products.index') }}" class="btn btn-secondary mt-3">Voltar</a>
        </form>
    </div>
</div>
@endsection
