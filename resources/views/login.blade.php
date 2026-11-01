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

      <div class="contenier">
              <div>
        <span style= "color: black;">Lembrar-me</span>
    </div>
    <div class="esquerda">
        <input type="checkbox" name="remember" class="checkbox">
    </div>

</div>
<div class="senha">
    @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline">
            Esqueceu sua senha?
        </a>
    @endif
</div>


            <button type="submit" class="auth-btn">Entrar</button>
        </form>
    </div>
</div>
@endsection
<style>
/* CSS custom para sobrescrever o build */
.backdrop {
    background-color: transparent !important;
}
.auth-wrapper {
    background: transparent !important;
     background-color: transparent !important;
}

</style>

<style>

.contenier {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px; /* espaçamento entre checkbox e texto */
}</style>