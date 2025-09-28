@extends('layouts.admin')

@section('title', 'Editar Produto')

@section('header')
    Editar Produto
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('admin.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Categoria</label>
            <select name="id_categories" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->id_categories == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>

            <label>Título</label>
            <input type="text" name="title" class="form-control" value="{{ $product->title }}" required>

            <label>Descrição</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>

            <label>Preço</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>

            <label>Quantidade em Estoque</label>
            <input type="number" name="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" required>

            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="ativo" {{ $product->status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ $product->status == 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>

            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection
