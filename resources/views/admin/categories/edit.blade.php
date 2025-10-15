@extends('layouts.admin')

@section('title', 'Editar Categoria')

@section('header')
    Editar Categoria
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Nome da Categoria</label>
            <input type="text" name="category_name" class="form-control" value="{{ $category->category_name }}" required>

            <label>Descrição</label>
            <textarea name="description" class="form-control">{{ $category->description }}</textarea>

            <br>
            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection
