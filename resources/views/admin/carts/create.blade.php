@extends('layouts.admin')

@section('title', 'Novo Carrinho')

@section('header')
    Criar Carrinho
@endsection

@section('content')
<div class="container">
    <form action="{{ route('carts.store') }}" method="POST">
        @csrf
        <label>Cliente</label>
        <select name="id_clients">
            <option value="">Selecione</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->full_name }}</option>
            @endforeach
        </select>

        <label>Session ID</label>
        <input type="text" name="session_id" required>

        <button type="submit">Salvar</button>
        <a href="{{ route('carts.index') }}">Voltar</a>
    </form>
</div>
@endsection
