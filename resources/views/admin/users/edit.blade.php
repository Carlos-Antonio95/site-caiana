@extends('layouts.admin')

@section('title', 'Editar Usuário')
@section('header', 'Editar Usuário')

@section('content')
<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group mb-3">
        <label>Nome</label>
        <input 
            type="text" 
            name="name" 
            class="form-control" 
            value="{{ old('name', $user->name) }}" 
            required
        >
    </div>

    <div class="form-group mb-3">
        <label>Email</label>
        <input 
            type="email" 
            name="email" 
            class="form-control" 
            value="{{ old('email', $user->email) }}" 
            required
        >
    </div>

    <div class="form-group mb-3">
        <label>Papel</label>
        <select name="role" class="form-control" required>
            <option value="cliente" {{ $user->role === 'cliente' ? 'selected' : '' }}>Cliente</option>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
        </select>
    </div>

    <div class="form-group mb-3">
        <label>Nova Senha (opcional)</label>
        <input 
            type="password" 
            name="password" 
            class="form-control"
        >
    </div>

    <div class="form-group mb-4">
        <label>Confirme a Senha</label>
        <input 
            type="password" 
            name="password_confirmation" 
            class="form-control"
        >
    </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
</form>
<style>
.btn-primary {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: .95rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: none;
    background-color: darkblue;
    color: #fff;
    transition: all 0.2s ease-in-out;
}

.btn-primary:hover {
    background-color: #00008b; /* azul mais escuro ao passar o mouse */
}
</style>
@endsection
