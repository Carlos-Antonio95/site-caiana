@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Esqueceu sua senha?</h2>

        <div class="mb-4 text-sm text-gray-600">
            Informe seu endereço de e-mail e enviaremos um link para redefinir sua senha.
        </div>

        {{-- mensagem de status --}}
        @if (session('status'))
            <div class="auth-status mb-4">
                {{ session('status') }}
            </div>
        @endif

        {{-- mensagens de erro --}}
        @if ($errors->any())
            <div class="auth-error mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="auth-input">
            </div>

            <button type="submit" class="auth-btn">
                Enviar link de redefinição
            </button>
        </form>
    </div>
</div>
@endsection
