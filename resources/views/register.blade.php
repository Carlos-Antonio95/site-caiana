@extends('layouts.app')

@section('title', 'CAIANA — Registro')
@section('content')
<style>
    .auth-error-message {
        color: #ff0000; /* vermelho */
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .auth-input-error {
        border-color: #ff0000;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2 class="auth-title">Cadastrar</h2>

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
                <div class="auth-error-message" id="error-name"></div>
            </div>

            <div class="mb-4">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="auth-input">
                <div class="auth-error-message" id="error-email"></div>
            </div>

            <div class="mb-4">
                <label for="phone">Telefone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required class="auth-input">
                <div class="auth-error-message" id="error-phone"></div>
            </div>

            <div class="mb-4">
                <label for="date_birth">Data de Nascimento</label>
                <input id="date_birth" type="date" name="date_birth" value="{{ old('date_birth') }}" required class="auth-input">
                <div class="auth-error-message" id="error-date_birth"></div>
            </div>

            <div class="mb-4">
                <label for="password">Senha</label>
                <input id="password" type="password" name="password" required class="auth-input">
                <div class="auth-error-message" id="error-password"></div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation">Confirmar Senha</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="auth-input">
                <div class="auth-error-message" id="error-password_confirmation"></div>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const laravelErrors = @json($errors->messages());

    const messagesPt = {
        name: "Por favor, informe seu nome.",
        email: "Por favor, informe um email válido.",
        phone: "Por favor, informe um telefone válido (máx. 11 números).",
        date_birth: "Por favor, informe uma data de nascimento válida.",
        password: "A senha deve ter no mínimo 8 caracteres.",
        password_confirmation: "As senhas não coincidem."
    };

    // Função para mostrar erro
    function showError(input, message) {
        const errorDiv = document.getElementById(`error-${input.name}`);
        errorDiv.textContent = message;
        input.classList.add("auth-input-error");
    }

    // Função para limpar erro
    function clearError(input) {
        const errorDiv = document.getElementById(`error-${input.name}`);
        errorDiv.textContent = "";
        input.classList.remove("auth-input-error");
    }

    // Mostra erros do Laravel
    Object.keys(laravelErrors).forEach(function(field) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            showError(input, messagesPt[field] || laravelErrors[field][0]);
        }
    });

    // Validação em tempo real
    const phoneInput = document.querySelector("[name='phone']");
    phoneInput.addEventListener("input", function() {
        const phoneValue = phoneInput.value.replace(/\D/g,'');
        if (phoneValue.length > 11) {
            showError(phoneInput, messagesPt.phone);
        } else {
            clearError(phoneInput);
        }
    });

    const dateInput = document.querySelector("[name='date_birth']");
    dateInput.addEventListener("change", function() {
        const dateValue = new Date(dateInput.value);
        const today = new Date();
        today.setHours(0,0,0,0);
        if (dateValue >= today) {
            showError(dateInput, messagesPt.date_birth);
        } else {
            clearError(dateInput);
        }
    });

    const form = document.querySelector("form");
    form.addEventListener("submit", function(e) {
        let hasError = false;

        // Limpar erros anteriores (exceto em tempo real, mas mantém validações)
        document.querySelectorAll(".auth-error-message").forEach(el => {
            if(el.textContent === "") el.textContent = "";
        });
        document.querySelectorAll(".auth-input").forEach(el => el.classList.remove("auth-input-error"));

        // Validação final antes de enviar
        const phoneValue = phoneInput.value.replace(/\D/g,'');
        if (phoneValue.length > 11) {
            showError(phoneInput, messagesPt.phone);
            hasError = true;
        }

        const dateValue = new Date(dateInput.value);
        const today = new Date();
        today.setHours(0,0,0,0);
        if (dateValue >= today) {
            showError(dateInput, messagesPt.date_birth);
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
        }
    });
});
</script>

@endsection
