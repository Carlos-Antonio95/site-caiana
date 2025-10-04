@extends('layouts.admin')

@section('title', 'Cupons')

@section('header')
    Lista de Cupons
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Cupons</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary mb-3">+ Novo Cupom</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Data Expiração</th>
                    <th>Usos Máx</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ ucfirst($coupon->discount_type) }}</td>
                        <td>
                            @if($coupon->discount_type === 'percentual')
                                {{ $coupon->discount_value }}%
                            @else
                                R$ {{ number_format($coupon->discount_value, 2, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($coupon->expiration_date)->format('d/m/Y') }}</td>
                        <td>{{ $coupon->max_use }}</td>
                        <td>{{ $coupon->active ? 'Ativo' : 'Inativo' }}</td>
                        <td>
                            <a href="{{ route('coupons.show', $coupon) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Excluir cupom?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Nenhum cupom encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
