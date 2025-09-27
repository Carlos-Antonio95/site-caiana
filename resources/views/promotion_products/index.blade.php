@extends('layouts.app')

@section('content')
<title>CAIANA — Promoções por Produto</title>

<div class="container py-4">
    <h1 class="mb-4">Promoções Aplicadas aos Produtos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome promoção</th>  
                    <th>Nome produto</th>
                    <th>Desconto (%)</th>
                    <th>Preço Promocional</th>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Nenhuma promoção aplicada a produtos ainda.</p>
    @endif
</div>
@endsection
