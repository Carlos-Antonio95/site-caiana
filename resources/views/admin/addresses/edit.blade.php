@extends('layouts.admin')

@section('title', 'Editar Endereço')

@section('header')
    Editar Endereço
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('addresses.update', $addresses) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Rua</label>
            <input type="text" name="road" class="form-control" value="{{ $addresses->road }}" required>

            <label>Número</label>
            <input type="text" name="number" class="form-control" value="{{ $addresses->number }}" required>

            <label>Complemento</label>
            <input type="text" name="complement" class="form-control" value="{{ $addresses->complement }}">

            <label>Referência</label>
            <input type="text" name="referenc" class="form-control" value="{{ $addresses->referenc }}">

            <label>Bairro</label>
            <input type="text" name="neighborhood" class="form-control" value="{{ $addresses->neighborhood }}" required>

            <label>Cidade</label>
            <input type="text" name="city" class="form-control" value="{{ $addresses->city }}" required>

            <label>Estado</label>
            <input type="text" name="state" class="form-control" value="{{ $addresses->state }}" required>

            <label>CEP</label>
            <input type="text" name="cep" class="form-control" value="{{ $addresses->cep }}" required>

            <label>País</label>
            <input type="text" name="country" class="form-control" value="{{ $addresses->country }}" required>

            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
@endsection
