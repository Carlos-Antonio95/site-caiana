@extends('layouts.admin')

@section('title', 'Usuários')
@section('header', 'Lista de Usuários')

@section('content')
<div class="container">
    <div class="card">
        <h2>Usuários</h2>

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-4">+ Novo Usuário</a>

    {{-- Mensagens de sucesso ou erro --}}
    @if(session('success'))
        <div class="alert alert-success" style="background:#d4edda;color:#155724;padding:10px;border-radius:5px;margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="background:#f8d7da;color:#721c24;padding:10px;border-radius:5px;margin-bottom:15px;">
            {{ session('error') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Role</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Editar</a>

                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>

                    @if($user->role !== 'admin')
                        <form action="{{ route('admin.users.promote', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-success">Promover Admin</button>
                        </form>
                    @endif

                    @if($user->role === 'admin' && Auth::id() !== $user->id)
                        <form action="{{ route('admin.users.demote', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-secondary">Rebaixar para Cliente</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
        
    </div>
</div>
    {{-- Opcional: mensagem desaparece após 4s --}}
    <script>
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => el.remove());
        }, 4000);
    </script>

@endsection
