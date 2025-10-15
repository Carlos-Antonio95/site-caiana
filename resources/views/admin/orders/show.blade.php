@extends('layouts.admin')

@section('title', 'Detalhes do Pedido')

@section('header')
    Detalhes do Pedido
@endsection

@section('content')
<div class="container">
    <div class="card">
    @if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif


        <h2>Pedido #{{ $order->id }}</h2>

        <p><strong>Cliente:</strong> {{ $order->client->full_name ?? '-' }}</p>
        <p><strong>Endereço:</strong> {{ $order->address->road ?? '-' }}, {{ $order->address->number ?? '' }}</p>
        <p><strong>Total:</strong> R$ {{ number_format($order->total_value, 2, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

        <hr>
        <h3>Itens do Pedido</h3>
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('order_items.create', ['order_id' => $order->id]) }}" class="btn btn-primary mb-2">+ Adicionar Item</a>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Variante</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                    @if(Auth::user()->role === 'admin')
                        <th>Ações</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td>{{ $item->title ?? $item->product_name }}</td>
                        <td>{{ $item->variant->name ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                        @if(Auth::user()->role === 'admin')
                            <td>
                                <a href="{{ route('order_items.edit', $item) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('order_items.destroy', $item) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Excluir item?')">Excluir</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ Auth::user()->role === 'admin' ? 6 : 5 }}">Nenhum item neste pedido.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if(Auth::user()->role === 'admin')
            <hr>
            <h4>Alterar Status</h4>
            <form id="changeStatusForm" action="{{ route('admin.orders.changeStatusTest', $order) }}" method="POST">
                @csrf
                <label>Status:</label>
                <select name="status" class="form-control" onchange="this.form.submit()">
                    @php
                        $statuses = ['pendente', 'aprovado', 'pago', 'enviado', 'entregue', 'cancelado'];
                    @endphp
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif

        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-2">Voltar</a>
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning mt-2">Editar</a>
        @endif
    </div>
</div>
<style>
.alert-success {
    background-color: #d4edda; /* verde claro */
    color: #155724; /* verde escuro no texto */
    border: 1px solid #c3e6cb;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-weight: 500;
}
</style>

@endsection
