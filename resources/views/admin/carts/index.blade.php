@extends('layouts.admin')

@section('title', 'Carrinhos')

@section('header')
    Lista de Carrinhos
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Carrinhos</h2>
        {{-- Link para criar um novo carrinho (admin) --}}
        <a href="{{ route('carts.create') }}" class="btn btn-primary">+ Novo Carrinho</a>

        <table class="table">
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
                        <td>{{ $cart->client->full_name ?? 'Cliente não encontrado' }}</td>
                        <td>{{ $cart->session_id }}</td>
                        <td>
                            {{-- Ver detalhes do carrinho --}}
                            <a href="{{ route('carts.show', $cart) }}" class="btn btn-info">Ver</a>
                            {{-- Editar (admin) --}}
                            <a href="{{ route('carts.edit', $cart) }}" class="btn btn-warning">Editar</a>
                            {{-- Excluir --}}
                            <form action="{{ route('carts.destroy', $cart) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Excluir carrinho?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Nenhum carrinho encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
