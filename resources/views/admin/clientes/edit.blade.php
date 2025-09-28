@extends('layouts.admin')

@section('title', 'Editar Cliente')

@section('header')
    Editar Cliente
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Nome Completo</label>
            <input type="text" name="full_name" class="form-control" value="{{ $client->full_name }}" required>

            <label>Telefone</label>
            <input type="text" name="phone" class="form-control" value="{{ $client->phone }}" required>

            <label>Data de Nascimento</label>
            <input type="date" name="date_birth" class="form-control" value="{{ $client->date_birth }}" required>

            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection
