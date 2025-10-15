@extends('layouts.admin')

@section('title', 'Promoções')

@section('header')
    Lista de Promoções
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Promoções</h2>
        <a href="{{ route('promotions.create') }}" class="btn btn-primary mb-3">+ Nova Promoção</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $promotion)
                    <tr>
                        <td>{{ $promotion->id }}</td>
                        <td>{{ $promotion->name }}</td>
                        <td>{{ $promotion->start_date }}</td>
                        <td>{{ $promotion->end_date }}</td>
                        <td>{{ $promotion->active ? 'Ativo' : 'Inativo' }}</td>
                        <td>
                            <a href="{{ route('promotions.show', $promotion) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Excluir promoção?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Nenhuma promoção encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
