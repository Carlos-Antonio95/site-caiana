@extends('layouts.admin')

@section('title', 'Novo Cupom')

@section('header')
    Cadastrar Cupom
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('coupons.store') }}" method="POST">
            @csrf

            <label>Código</label>
            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>

            <label>Tipo de Desconto</label>
            <select name="discount_type" class="form-control" required>
                <option value="valor" {{ old('discount_type') == 'valor' ? 'selected' : '' }}>Valor Fixo (R$)</option>
                <option value="percentual" {{ old('discount_type') == 'percentual' ? 'selected' : '' }}>Percentual (%)</option>
            </select>

            <label>Valor do Desconto</label>
            <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required>

            <label>Valor Mínimo de Pedido</label>
            <input type="number" step="0.01" name="min_discount" class="form-control" value="{{ old('min_discount') }}" required>

            <label>Data de Expiração</label>
            <input type="date" name="expiration_date" class="form-control" value="{{ old('expiration_date') }}" required>

            <label>Máximo de Usos</label>
            <input type="number" name="max_use" class="form-control" value="{{ old('max_use') }}" required>

            <label>Status</label>
            <select name="active" class="form-control">
                <option value="1" {{ old('active') == 1 ? 'selected' : '' }}>Ativo</option>
                <option value="0" {{ old('active') == 0 ? 'selected' : '' }}>Inativo</option>
            </select>

            <button type="submit" class="btn btn-success mt-3">Salvar</button>
            <a href="{{ route('coupons.index') }}" class="btn btn-secondary mt-3">Voltar</a>
        </form>
    </div>
</div>
@endsection
