@extends('layouts.admin')

@section('title', 'Detalhes do Cliente')

@section('header')
    Detalhes do Cliente
@endsection

@section('content')
<div class="container">
    <div class="card">
        <p><strong>Nome:</strong> {{ $client->full_name }}</p>
        <p><strong>Telefone:</strong> {{ $client->phone }}</p>
        <p><strong>Data de Nascimento:</strong> {{ $client->date_birth }}</p>

        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Editar</a>
    </div>
</div>
@endsection
