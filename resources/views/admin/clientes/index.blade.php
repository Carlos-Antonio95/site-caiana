@extends('layouts.admin')

@section('title', 'Clientes')

@section('header')
    Lista de Clientes
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Clientes</h2>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">+ Novo Cliente</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Nome Completo</th>
                    <th>Telefone</th>
                    <th>Data de Nascimento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->full_name }}</td>
                        <td>{{ $client->phone }}</td>
                        <td>{{ $client->date_birth }}</td>
                        <td>
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Excluir cliente?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Nenhum cliente encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
