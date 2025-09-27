@extends('layouts.app')

@section('content')
<title>CAIANA — Teste Promoções</title>

<div class="container py-4">
    <h1 class="mb-4">Teste de Promoções</h1>

    {{-- Mensagens de sucesso --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Criar promoção --}}
    <div class="card mb-4">
        <div class="card-header">Criar Promoção</div>
        <div class="card-body">
            <form action="{{ route('promotions.index.create') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Nome</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Descrição</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Data de Início</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Data de Fim</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Banner (URL opcional)</label>
                    <input type="text" name="banner" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Ativa?</label>
                    <select name="active" class="form-control" required>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Criar Promoção</button>
            </form>
        </div>
    </div>

    {{-- Adicionar produto à promoção --}}
    <div class="card mb-4">
        <div class="card-header">Adicionar Produto à Promoção</div>
        <div class="card-body">
            <form action="{{ route('promotions.index.addProduct') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>ID da Promoção</label>
                    <input type="number" name="id_promotions" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>ID do Produto</label>
                    <input type="number" name="id_products" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Desconto (%)</label>
                    <input type="number" step="0.01" name="percentage_discount" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Preço Promocional (fixo)</label>
                    <input type="number" step="0.01" name="promotional_price" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Adicionar Produto</button>
            </form>
        </div>
    </div>

    {{-- Listagem de promoções --}}
    <div class="card">
        <div class="card-header">Promoções Criadas</div>
        <div class="card-body">
            @if($promotions->count())
                <ul class="list-group">
                    @foreach($promotions as $promotion)
                        <li class="list-group-item">
                            <strong>{{ $promotion->name }}</strong>  
                            ({{ $promotion->start_date }} até {{ $promotion->end_date }})
                            <br>
                            <strong>ID da Promoção:</strong> {{ $promotion->id }}
                            <br>
                            {{ $promotion->description }}
                            <br>
                            Status: {{ $promotion->active ? 'Ativa' : 'Inativa' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Nenhuma promoção criada ainda.</p>
            @endif
        </div>
    </div>
</div>
@endsection
