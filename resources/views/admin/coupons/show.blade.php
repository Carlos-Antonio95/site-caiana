@extends('layouts.admin')

@section('title', 'Detalhes do Cupom')

@section('header')
    Detalhes do Cupom
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>{{ $coupon->code }}</h2>
        <p><strong>Tipo:</strong> {{ ucfirst($coupon->discount_type) }}</p>
        <p><strong>Valor:</strong>
            @if($coupon->discount_type === 'percentual')
                {{ $coupon->discount_value }}%
            @else
                R$ {{ number_format($coupon->discount_value, 2, ',', '.') }}
            @endif
        </p>
        <p><strong>Valor mínimo:</strong> R$ {{ number_format($coupon->min_discount, 2, ',', '.') }}</p>
        <p><strong>Expira em:</strong> {{ \Carbon\Carbon::parse($coupon->expiration_date)->format('d/m/Y') }}</p>
        <p><strong>Usos Máximos:</strong> {{ $coupon->max_use }}</p>
        <p><strong>Status:</strong> {{ $coupon->active ? 'Ativo' : 'Inativo' }}</p>

        <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Voltar</a>
        <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('Excluir cupom?')">Excluir</button>
        </form>
    </div>
</div>
@endsection
