@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Login</h2>

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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="auth-input">
            </div>

            <div class="mb-4">
                <label for="password">Senha</label>
                <input id="password" type="password" name="password" required class="auth-input">
            </div>

            <div class="flex items-center justify-between mb-4">
                <label>
                    <input type="checkbox" name="remember"> Lembrar-me
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link">
                        Esqueceu sua senha?
                    </a>
                @endif
            </div>

            <button type="submit" class="auth-btn">Entrar</button>
        </form>
    </div>
</div>
@endsection
