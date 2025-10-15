@extends('layouts.admin')

@section('title', 'Novo Cliente')

@section('header')
    Cadastrar Cliente
@endsection

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('admin.clients.store') }}" method="POST">
            @csrf
            <label>Nome Completo</label>
            <input type="text" name="full_name" class="form-control" required>

            <label>Telefone</label>
            <input type="text" name="phone" class="form-control" required>

            <label>Data de Nascimento</label>
            <input type="date" name="date_birth" class="form-control" required>

            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
        
<div id="success-notif" class="notification success"></div>
<div id="error-notif" class="notification error"></div>

<style>
.notification {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 15px 20px;
  border-radius: 8px;
  color: #fff;
  opacity: 0;
  transform: translateY(-20px);
  transition: opacity 0.3s, transform 0.3s;
  z-index: 9999;
  margin-top: 10px;
}
.notification.show { opacity: 1; transform: translateY(0); }
.success { background: #16a34a; } /* verde */
.error   { background: #dc2626; } /* vermelho */
</style>

<script>
function showNotification(message, type='success') {
  const notif = type === 'success'
    ? document.getElementById('success-notif')
    : document.getElementById('error-notif');
  
  notif.textContent = message;
  notif.classList.add('show');

  setTimeout(() => notif.classList.remove('show'), 3000);
}

// Exemplo: disparar notificação após envio do form
document.querySelector('form').addEventListener('submit', function(e) {
  // Aqui você poderia fazer validação ou AJAX
  e.preventDefault(); // Remove isso se for enviar normal

  // Exemplo de sucesso
  showNotification('Cliente cadastrado com sucesso!', 'success');

  // Se quiser simular erro:
  // showNotification('Erro ao cadastrar cliente!', 'error');
});
</script>

    </div>
</div>
@endsection
