@extends('layouts.admin')

@section('title', 'Detalhes da Promoção')

@section('header')
    Detalhes da Promoção
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>{{ $promotion->name }}</h2>
        <p><strong>Descrição:</strong> {{ $promotion->description }}</p>
        <p><strong>Data de Início:</strong> {{ $promotion->start_date }}</p>
        <p><strong>Data de Fim:</strong> {{ $promotion->end_date }}</p>
        <p><strong>Banner:</strong> {{ $promotion->banner ?? '-' }}</p>
        <p><strong>Status:</strong> {{ $promotion->active ? 'Ativo' : 'Inativo' }}</p>

        <a href="{{ route('promotions.index') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('promotions.edit', $promotion) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('promotions.destroy', $promotion) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('Excluir promoção?')">Excluir</button>
        </form>
    </div>
</div>
@endsection
