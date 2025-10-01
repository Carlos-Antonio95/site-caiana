@extends('layouts.admin')

@section('title', 'Categorias')

@section('header')
    Lista de Categorias
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Categorias</h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Nova Categoria</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->description ?? '-' }}</td>
                        <td>
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Excluir categoria?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">Nenhuma categoria encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
