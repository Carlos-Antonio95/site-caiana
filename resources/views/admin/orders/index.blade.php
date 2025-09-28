@extends('layouts.admin')

@section('title', 'Pedidos')

@section('header')
    Lista de Pedidos
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Pedidos</h2>
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">+ Novo Pedido</a>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Endereço</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->client->full_name ?? '-' }}</td>
                        <td>{{ $order->address->road ?? '-' }}</td>
                        <td>R$ {{ number_format($order->total_value, 2, ',', '.') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info">Ver</a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">Editar</a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" onclick="return confirm('Excluir pedido?')">Excluir</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Nenhum pedido encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
