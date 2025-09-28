@extends('layouts.admin')

@section('title', 'Editar Carrinho')

@section('header')
    Editar Carrinho #{{ $cart->id }}
@endsection

@section('content')
<div class="container">
    <form action="{{ route('carts.update', $cart) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Cliente</label>
        <select name="id_clients">
            <option value="">Selecione</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ $cart->id_clients == $client->id ? 'selected' : '' }}>
                    {{ $client->full_name }}
                </option>
            @endforeach
        </select>

        <label>Session ID</label>
        <input type="text" name="session_id" value="{{ $cart->session_id }}" required>

        <button type="submit">Atualizar</button>
        <a href="{{ route('carts.index') }}">Voltar</a>
    </form>
</div>
@endsection
