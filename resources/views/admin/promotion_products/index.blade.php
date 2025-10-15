@extends('layouts.admin')

@section('title', 'Promoções por Produto')

@section('header')
    Promoções Aplicadas aos Produtos
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Promoções Aplicadas</h2>
        <a href="{{ route('promotion_products.create') }}" class="btn btn-primary mb-3">+ Nova Promoção de Produto</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($items->count())
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Promoção</th>
                        <th>Produto</th>
                        <th>Desconto (%)</th>
                        <th>Preço Promocional</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->promotion->name ?? '-' }}</td>
                            <td>{{ $item->product->title ?? '-' }}</td>
                            <td>{{ $item->percentage_discount ?? '-' }}</td>
                            <td>{{ $item->promotional_price ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.promotion_products.show', $item) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('promotion_products.edit', $item) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('promotion_products.destroy', $item) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Remover promoção do produto?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Nenhuma promoção aplicada a produtos ainda.</p>
        @endif
    </div>
</div>
@endsection
