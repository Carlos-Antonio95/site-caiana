@extends('layouts.admin')

@section('title', 'Mensagens de Contato')

@section('content')
<div class="container mt-4">
    <h2>Mensagens de Contato</h2>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Mensagem</th>
                <th>Respondido</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->nome }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ Str::limit($contact->mensagem, 50) }}</td>
                    <td>
                        <span class="badge {{ $contact->respondido ? 'bg-success' : 'bg-warning' }}">
                            {{ $contact->respondido ? 'Sim' : 'Não' }}
                        </span>
                    </td>
                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('admin.contacts.edit', $contact) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Excluir esta mensagem?')" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Nenhuma mensagem encontrada.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $contacts->links() }}
</div>
@endsection
