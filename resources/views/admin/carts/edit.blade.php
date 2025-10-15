@extends('layouts.admin')

@section('title', 'Editar Carrinho')
@section('header', 'Editar Carrinho')

@section('content')
<div class="container">

    <form action="{{ route('admin.carts.update', $cart) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label>Cliente</label>
            <select name="id_clients" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $cart->id_clients == $client->id ? 'selected' : '' }}>
                        {{ $client->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label>Session ID</label>
            <input type="text" name="session_id" value="{{ $cart->session_id }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('admin.carts.index') }}" class="btn btn-dark">Voltar</a>
    </form>
</div>
@endsection
