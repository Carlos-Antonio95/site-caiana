@extends('layouts.app')

@section('title', 'CAIANA — Contato')

@section('nav-contato', 'is-active')

@section('content')
<style>
/* ======== Estilos do formulário de contato ======== */
.contato-container {
    padding-top: 2rem;
    max-width: 500px;
    margin: 0 auto;
}

.contato-container h2 {
    text-align: center;
    margin-bottom: 1.5rem;
    color: #004b78;
}

form#contato-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.85);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid #d9ebff;
    transition: all 0.3s ease;
}

form#contato-form label {
    font-weight: 600;
    color: #00385a;
}

form#contato-form input,
form#contato-form textarea {
    width: 100%;
    padding: 0.75rem;
    border-radius: 8px;
    border: 1.5px solid #b9dfff;
    outline: none;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    font-family: inherit;
}

form#contato-form input:focus,
form#contato-form textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
}

form#contato-form button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 0.8rem;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.1s ease;
}

form#contato-form button:hover {
    background-color: #005ec2;
}

form#contato-form button:active {
    transform: scale(0.97);
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
    font-weight: 600;
    text-align: center;
}

.alert-success {
    background: #d8f5e0;
    color: #218838;
    border: 1px solid #a5e1b6;
}

.alert-error {
    background: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
}

.alert-loading {
    background: #e7f1ff;
    color: #004085;
    border: 1px solid #b6d4fe;
}

@media (max-width: 600px) {
    form#contato-form {
        padding: 1.5rem;
    }
}
</style>

<section class="container contato-container">
    <h2>Fale Conosco</h2>

    <form id="contato-form" method="POST" action="{{ route('contato.store') }}">
        @csrf
        <label>
            Nome
            <input type="text" name="nome" required>
        </label>
        <label>
            E-mail
            <input type="email" name="email" required>
        </label>
        <label>
            Mensagem
            <textarea name="mensagem" rows="5" required></textarea>
        </label>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>

    <div id="alert-container"></div>
</section>

<script>
const form = document.getElementById('contato-form');
const alertBox = document.getElementById('alert-container');

function showAlert(message, type) {
    alertBox.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
}

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    alertBox.innerHTML = '';
    
    // Validação simples no frontend
    const nome = form.nome.value.trim();
    const email = form.email.value.trim();
    const mensagem = form.mensagem.value.trim();

    if (!nome || !email || !mensagem) {
        showAlert('Por favor, preencha todos os campos.', 'error');
        return;
    }

    showAlert('Enviando mensagem...', 'loading');
    form.querySelector('button').disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: new FormData(form)
        });

        const data = await response.json();

        if (response.ok && data.success) {
            form.reset();
            form.style.display = 'none';
            showAlert('✅ Obrigado pelo contato! Em breve retornaremos.', 'success');
        } else {
            showAlert('❌ Ocorreu um erro ao enviar. Tente novamente mais tarde.', 'error');
        }
    } catch (error) {
        showAlert('❌ Falha de conexão. Verifique sua internet e tente novamente.', 'error');
    } finally {
        form.querySelector('button').disabled = false;
    }
});
</script>
@endsection
