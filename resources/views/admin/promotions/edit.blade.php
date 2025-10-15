@extends('layouts.admin')

@section('title', 'Editar Promoção')

@section('header')
    Editar Promoção
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('promotions.update', $promotion) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $promotion->name) }}" required>

            <label>Descrição</label>
            <textarea name="description" class="form-control" required>{{ old('description', $promotion->description) }}</textarea>

            <label>Data de Início</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $promotion->start_date) }}" required>

            <label>Data de Fim</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $promotion->end_date) }}" required>

            <label>Banner (URL ou caminho)</label>
            <input type="text" name="banner" class="form-control" value="{{ old('banner', $promotion->banner) }}">

            <label>Status</label>
            <select name="active" class="form-control">
                <option value="1" {{ old('active', $promotion->active) == 1 ? 'selected' : '' }}>Ativo</option>
                <option value="0" {{ old('active', $promotion->active) == 0 ? 'selected' : '' }}>Inativo</option>
            </select>

            <button type="submit" class="btn btn-success mt-3">Atualizar</button>
            <a href="{{ route('promotions.index') }}" class="btn btn-secondary mt-3">Voltar</a>
        </form>
    </div>
</div>
@endsection
