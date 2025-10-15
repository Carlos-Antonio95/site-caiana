@extends('layouts.app')

@section('title', 'Meus Pedidos')

@section('content')
<main style="padding:2rem; max-width:800px; margin:auto;">
    <h1>Meus Pedidos</h1>

    @if($orders->isEmpty())
        <p>Você não possui pedidos pendentes ou em andamento.</p>
    @else
        <table style="width:100%; border-collapse:collapse; margin-top:2rem;">
            <thead>
                <tr style="border-bottom:1px solid #ccc;">
                    <th>ID</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr style="border-bottom:1px solid #eee;">
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>R$ {{ number_format($order->total_value, 2, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" 
                           style="color:blue; text-decoration:underline;">Detalhes</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</main>
@endsection
