@extends('layouts.admin')

@section('title', 'Editar Mensagem')

@section('content')
<div class="container mt-4">
    <h2>Atualizar Status</h2>
    <form method="POST" action="{{ route('admin.contacts.update', $contact) }}" class="mt-3">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label>Status:</label>
            <select name="respondido" class="form-control" required>
                <option value="0" {{ !$contact->respondido ? 'selected' : '' }}>Não Respondido</option>
                <option value="1" {{ $contact->respondido ? 'selected' : '' }}>Respondido</option>
            </select>
        </div>

        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
