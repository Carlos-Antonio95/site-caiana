@extends('layouts.admin')

@section('title', 'Detalhes do Carrinho')

@section('header')
    Detalhes do Carrinho
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Detalhes do Carrinho</h2>

        {{-- Informações do carrinho --}}
        <p><strong>ID do Carrinho:</strong> {{ $cart->id }}</p>
        <p><strong>Cliente:</strong> {{ $cart->client->full_name ?? '-' }}</p>
        <p><strong>Session ID:</strong> {{ $cart->session_id }}</p>
        <p><strong>Endereço:</strong> {{ $cart->address->road ?? '-' }}, {{ $cart->address->number ?? '' }}</p>

        <hr>
        <h3>Itens do Carrinho</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cart->items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Nenhum item neste carrinho.</td></tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('carts.index') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('carts.edit', $cart) }}" class="btn btn-warning">Editar</a>
    </div>
</div>
@endsection
