@extends('layouts.admin')

@section('title', 'Produtos')

@section('header')
    Lista de Produtos
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Produtos</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Novo Produto</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->category->category_name ?? '-' }}</td>
                        <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>{{ ucfirst($product->status) }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Excluir produto?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Nenhum produto encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
