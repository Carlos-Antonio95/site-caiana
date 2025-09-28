@extends('layouts.admin')

@section('title', 'Carrinhos')

@section('header')
    Lista de Carrinhos
@endsection

@section('content')
<div class="container">
    <h2>Carrinhos</h2>
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.carts.create') }}" class="btn btn-primary">+ Novo Carrinho</a>
    @endif

    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Session ID</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($carts as $cart)
                <tr>
                    <td>{{ $cart->id }}</td>
                    <td>{{ $cart->client->full_name ?? '-' }}</td>
                    <td>{{ $cart->session_id }}</td>
                    <td>
                        <a href="{{ route('admin.carts.show', $cart) }}" class="btn btn-info">Ver</a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.carts.edit', $cart) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('admin.carts.destroy', $cart) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Excluir carrinho?')">Excluir</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Nenhum carrinho encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
