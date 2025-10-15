@extends('layouts.admin')

@section('title', 'Criar Usuário')
@section('header', 'Novo Usuário')

@section('content')
<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <div class="form-group mb-3">
        <label>Nome</label>
        <input 
            type="text" 
            name="name" 
            class="form-control" 
            value="{{ old('name') }}" 
            required
        >
    </div>

    <div class="form-group mb-3">
        <label>Email</label>
        <input 
            type="email" 
            name="email" 
            class="form-control" 
            value="{{ old('email') }}" 
            required
        >
    </div>

    <div class="form-group mb-3">
        <label>Papel</label>
        <select name="role" class="form-control" required>
            <option value="cliente" {{ old('role') === 'cliente' ? 'selected' : '' }}>Cliente</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
        </select>
    </div>

    <div class="form-group mb-3">
        <label>Senha</label>
        <input 
            type="password" 
            name="password" 
            class="form-control" 
            required
        >
    </div>

    <div class="form-group mb-4">
        <label>Confirme a Senha</label>
        <input 
            type="password" 
            name="password_confirmation" 
            class="form-control" 
            required
        >
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
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
    background-color: #00008b;
}
</style>
@endsection
