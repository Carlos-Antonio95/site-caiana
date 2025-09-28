@extends('layouts.admin')

@section('title', 'Novo Carrinho')

@section('header')
    Cadastrar Carrinho
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('carts.store') }}" method="POST">
            @csrf

            <label>Cliente</label>
            <select name="id_clients" class="form-control">
                <option value="">Selecione o cliente</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                @endforeach
            </select>

            <label>Session ID</label>
            <input type="text" name="session_id" class="form-control" required>

            <button type="submit" class="btn btn-success mt-2">Salvar</button>
            <a href="{{ route('carts.index') }}" class="btn btn-secondary mt-2">Voltar</a>
        </form>
    </div>
</div>
@endsection
