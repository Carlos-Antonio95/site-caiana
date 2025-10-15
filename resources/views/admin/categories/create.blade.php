@extends('layouts.admin')

@section('title', 'Nova Categoria')

@section('header')
    Cadastrar Categoria
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <label>Nome da Categoria</label>
            <input type="text" name="category_name" class="form-control" required>

            <label>Descrição</label>
            <textarea name="description" class="form-control"></textarea>

            <br>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection
    