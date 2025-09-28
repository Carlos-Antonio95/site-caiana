@extends('layouts.app')
@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Cadastrar</h2>

        {{-- mensagens de erro --}}
        @if ($errors->any())
            <div class="auth-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- mensagem de status --}}
        @if (session('status'))
            <div class="auth-status">
                {{ session('status') }}
            </div>
        @endif
        

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name">Nome</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="auth-input">
            </div>

            <div class="mb-4">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="auth-input">
            </div>

            {{-- Novo campo: Telefone --}}
            <div class="mb-4">
                <label for="phone">Telefone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required class="auth-input">
            </div>

            {{-- Novo campo: Data de nascimento --}}
            <div class="mb-4">
                <label for="date_birth">Data de Nascimento</label>
                <input id="date_birth" type="date" name="date_birth" value="{{ old('date_birth') }}" required class="auth-input">
            </div>

            <div class="mb-4">
                <label for="password">Senha</label>
                <input id="password" type="password" name="password" required class="auth-input">
            </div>

            <div class="mb-4">
                <label for="password_confirmation">Confirmar Senha</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="auth-input">
            </div>

            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('login') }}" class="auth-link">
                    Já possui conta? Entrar
                </a>
            </div>

            <button type="submit" class="auth-btn">Cadastrar</button>
        </form>
    </div>
</div>

@endsection
