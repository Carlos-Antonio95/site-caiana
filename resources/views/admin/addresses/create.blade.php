@extends('layouts.admin')

@section('title', 'Novo Endereço')

@section('header')
    Cadastrar Endereço
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('admin.addresses.store') }}" method="POST">
            @csrf
            <label>Rua</label>
            <input type="text" name="road" class="form-control" required>

            <label>Número</label>
            <input type="text" name="number" class="form-control" required>

            <label>Complemento</label>
            <input type="text" name="complement" class="form-control">

            <label>Referência</label>
            <input type="text" name="referenc" class="form-control">

            <label>Bairro</label>
            <input type="text" name="neighborhood" class="form-control" required>

            <label>Cidade</label>
            <input type="text" name="city" class="form-control" required>

            <label>Estado</label>
            <input type="text" name="state" class="form-control" required>

            <label>CEP</label>
            <input type="text" name="cep" class="form-control" required>

            <label>País</label>
            <input type="text" name="country" class="form-control" required>

            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection
