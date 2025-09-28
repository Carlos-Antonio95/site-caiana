@extends('layouts.admin')

@section('title', 'Novo Cliente')

@section('header')
    Cadastrar Cliente
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('admin.clients.store') }}" method="POST">
            @csrf
            <label>Nome Completo</label>
            <input type="text" name="full_name" class="form-control" required>

            <label>Telefone</label>
            <input type="text" name="phone" class="form-control" required>

            <label>Data de Nascimento</label>
            <input type="date" name="date_birth" class="form-control" required>

            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection
