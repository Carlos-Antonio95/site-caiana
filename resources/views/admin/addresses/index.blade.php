@extends('layouts.admin')

@section('title', 'Endereços')

@section('header')
    Lista de Endereços
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Endereços</h2>
        <a href="{{ route('admin.addresses.create') }}" class="btn btn-primary">+ Novo Endereço</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Rua</th>
                    <th>Número</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($addresses as $address)
                    <tr>
                        <td>{{ $address->road }}</td>
                        <td>{{ $address->number }}</td>
                        <td>{{ $address->city }}</td>
                        <td>{{ $address->state }}</td>
                        <td>
                            <a href="{{ route('admin.addresses.show', $address) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('admin.addresses.edit', $address) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Excluir endereço?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Nenhum endereço encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
