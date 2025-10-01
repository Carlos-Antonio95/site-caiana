@extends('layouts.admin')

@section('title', 'Detalhes da Categoria')

@section('header')
    Detalhes da Categoria
@endsection

@section('content')
<div class="container">
    <div class="card">
        <p><strong>Nome:</strong> {{ $category->category_name }}</p>
        <p><strong>Descrição:</strong> {{ $category->description ?? '-' }}</p>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Editar</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('Excluir categoria?')">Excluir</button>
        </form>
    </div>
</div>
@endsection
