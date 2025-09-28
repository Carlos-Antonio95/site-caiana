@extends('layouts.admin')

@section('title', 'Novo Pedido')

@section('header')
    Criar Pedido
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <label>Cliente</label>
            <select name="id_clients" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                @endforeach
            </select>

            <label>Endereço</label>
            <select name="id_addresses" class="form-control" required>
                <option value="">Selecione</option>
                @foreach($addresses as $address)
                    <option value="{{ $address->id }}">
                        {{ $address->road }}, {{ $address->number }}
                    </option>
                @endforeach
            </select>

            <label>Status</label>
            <select name="status" class="form-control" required>
                @foreach(['pendente','aprovado','pago','enviado','entregue','cancelado'] as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>

            <label>Total</label>
            <input type="number" name="total_value" class="form-control" min="0" step="0.01" required>

            <button type="submit" class="btn btn-success mt-2">Salvar</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-2">Voltar</a>
        </form>
    </div>
</div>
@endsection
