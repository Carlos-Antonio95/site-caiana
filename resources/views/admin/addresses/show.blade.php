@extends('layouts.admin')

@section('title', 'Detalhes do Endereço')

@section('header')
    Detalhes do Endereço
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Detalhes do Endereço</h2>
        <p><strong>Nome Cliente:</strong> {{ $addresses->client->full_name }}</p>
        <br>
        <p><strong>ID:</strong> {{ $addresses->id }}</p>
        <p><strong>Rua:</strong> {{ $addresses->road }}</p>
        <p><strong>Número:</strong> {{ $addresses->number }}</p>
        <p><strong>Complemento:</strong> {{ $addresses->complement ?? '-' }}</p>
        <p><strong>Referência:</strong> {{ $addresses->referenc ?? '-' }}</p>
        <p><strong>Bairro:</strong> {{ $addresses->neighborhood }}</p>
        <p><strong>Cidade:</strong> {{ $addresses->city }}</p>
        <p><strong>Estado:</strong> {{ $addresses->state }}</p>
        <p><strong>CEP:</strong> {{ $addresses->cep }}</p>
        <p><strong>País:</strong> {{ $addresses->country }}</p>

        <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('addresses.edit', $addresses) }}" class="btn btn-warning">Editar</a>
    </div>
</div>
@endsection
