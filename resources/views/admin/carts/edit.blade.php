@extends('layouts.admin')

@section('title', 'Editar Carrinho')

@section('header')
    Editar Carrinho
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('carts.update', $cart) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Cliente</label>
            <select name="id_clients" class="form-control">
                <option value="">Selecione o cliente</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $client->id == $cart->id_clients ? 'selected' : '' }}>
                        {{ $client->full_name }}
                    </option>
                @endforeach
            </select>

            <label>Session ID</label>
            <input type="text" name="session_id" class="form-control" value="{{ $cart->session_id }}" required>

            <button type="submit" class="btn btn-success mt-2">Atualizar</button>
            <a href="{{ route('carts.index') }}" class="btn btn-secondary mt-2">Voltar</a>
        </form>
    </div>
</div>
@endsection
