    @extends('layouts.admin')

@section('title', 'Detalhes da Mensagem')

@section('content')
<div class="container mt-4">
    <h2>Mensagem #{{ $contact->id }}</h2>
    <div class="card mt-3 p-4">
        <p><strong>Nome:</strong> {{ $contact->nome }}</p>
        <p><strong>Email:</strong> {{ $contact->email }}</p>
        <p><strong>Mensagem:</strong><br>{{ $contact->mensagem }}</p>
        <p><strong>Respondido:</strong> {{ $contact->respondido ? 'Sim' : 'Não' }}</p>
        <p><strong>Data:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary mt-3">Voltar</a>
</div>
@endsection
