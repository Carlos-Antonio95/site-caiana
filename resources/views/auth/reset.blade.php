@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Redefinir Senha</h2>

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

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-4">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus class="auth-input">
            </div>

            <div class="mb-4">
                <label for="password">Nova Senha</label>
                <input id="password" type="password" name="password" required class="auth-input">
            </div>

            <div class="mb-4">
                <label for="password_confirmation">Confirmar Senha</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="auth-input">
            </div>

            <button type="submit" class="auth-btn">Redefinir Senha</button>
        </form>
    </div>
</div>
@endsection
    