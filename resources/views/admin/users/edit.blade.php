@extends('layouts.admin')

@section('title', 'Editar Usuário')
@section('header', 'Editar Usuário')

@section('content')
<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
    </div>
    <div class="form-group">
        <label>Nova Senha (opcional)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="form-group">
        <label>Confirme a Senha</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary mt-2">Atualizar</button>
</form>
@endsection
