@extends('layouts.admin')

@section('title', 'Detalhes do Usuário')
@section('header', 'Detalhes do Usuário')

@section('content')
    <div class="card mb-4 p-4" style="background:#fff; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Nome:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ $user->role }}</p>
        <p><strong>Criado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Atualizado em:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>

        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning mt-3">Editar</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Voltar</a>
    </div>
@endsection
