@extends('layouts.admin')

@section('title', 'Editar Pedido')

@section('header')
    Editar Pedido
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Cliente</label>
            <select name="id_clients" class="form-control" required>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $order->id_clients === $client->id ? 'selected' : '' }}>
                        {{ $client->full_name }}
                    </option>
                @endforeach
            </select>

            <label>Endereço</label>
            <select name="id_addresses" class="form-control" required>
                @foreach($addresses as $address)
                    <option value="{{ $address->id }}" {{ $order->id_addresses === $address->id ? 'selected' : '' }}>
                        {{ $address->road }}, {{ $address->number }}
                    </option>
                @endforeach
            </select>

            <label>Status</label>
            <select name="status" class="form-control" required>
                @foreach(['pendente','aprovado','pago','enviado','entregue','cancelado'] as $status)
                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <label>Total</label>
            <input type="number" name="total_value" class="form-control" min="0" step="0.01" value="{{ $order->total_value }}" required>

            <button type="submit" class="btn btn-success mt-2">Atualizar</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-2">Voltar</a>
        </form>
    </div>
</div>
@endsection
