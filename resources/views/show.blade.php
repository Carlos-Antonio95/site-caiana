@extends('layouts.app')

@section('title', 'Detalhes do Pedido')

@section('content')
<main style="padding:2rem; max-width:800px; margin:auto;">
    <h1>Pedido #{{ $order->id }}</h1>
    <p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
    <p>Data: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p>Total: R$ {{ number_format($order->total_value, 2, ',', '.') }}</p>

    @if($order->address)
        <p>
            Endereço de entrega: {{ $order->address->road }}, {{ $order->address->number }}
            @if($order->address->complement)
                ({{ $order->address->complement }})
            @endif
            , {{ $order->address->neighborhood }}, {{ $order->address->city }}/{{ $order->address->state }}
            - CEP: {{ $order->address->cep }}
        </p>
    @endif

    <h2>Itens</h2>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->product->title }} - {{ $item->quantity }}x - R$ {{ number_format($item->price, 2, ',', '.') }}</li>
        @endforeach
    </ul>

    <a href="{{ route('index') }}" style="color:blue; text-decoration:underline;">Voltar</a>
</main>
@endsection
