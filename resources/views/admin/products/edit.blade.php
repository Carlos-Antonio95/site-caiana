@extends('layouts.admin')

@section('title', 'Editar Produto')

@section('header')
    Editar Produto
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Categoria -->
            <label>Categoria</label>
            <select name="id_categories" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->id_categories == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>

            <!-- Título -->
            <label>Título</label>
            <input type="text" name="title" class="form-control" value="{{ $product->title }}" required>

            <!-- Descrição -->
            <label>Descrição</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>

            <!-- Preço -->
            <label>Preço</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>

            <!-- Estoque -->
            <label>Quantidade em Estoque</label>
            <input type="number" name="stock_quantity" class="form-control" value="{{ $product->stock_quantity }}" required>

            <!-- Status -->
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="ativo" {{ $product->status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ $product->status == 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>

            <!-- Imagem atual -->
                <label>Imagem Atual</label><br>
                @php
                    $image = $product->images->first();
                @endphp
                @if($image)
                    <img src="{{ asset($image->image_path) }}" alt="{{ $product->title }}" width="100">
                @else
                    <p>Nenhuma imagem cadastrada.</p>
                @endif

                <!-- Nova imagem -->
                <label>Nova Imagem</label>
                <input type="file" name="image_path" class="form-control">

                <br>
                <button type="submit" class="btn btn-success">Atualizar</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Voltar</a>
            </form>
    </div>
</div>
@endsection
