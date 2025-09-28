@extends('layouts.admin')

@section('title', 'Novo Produto')

@section('header')
    Cadastrar Produto
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <label>Categoria</label>
            <select name="id_categories" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <label>Título</label>
            <input type="text" name="title" class="form-control" required>

            <label>Descrição</label>
            <textarea name="description" class="form-control"></textarea>

            <label>Preço</label>
            <input type="number" step="0.01" name="price" class="form-control" required>

            <label>Quantidade em Estoque</label>
            <input type="number" name="stock_quantity" class="form-control" required>

            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
            </select>

            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection
