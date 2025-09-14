@extends('layouts.app')

@section('title', 'CAIANA — Contato')

@section('nav-contato', 'is-active') <!-- Aba ativa -->

@section('content')
<section class="container" style="padding-top:2rem; max-width:500px;">
    <h2>Fale Conosco</h2>
    <form id="contato-form" style="display:flex; flex-direction:column; gap:1rem; background:rgba(255,255,255,.7); padding:2rem; border-radius:var(--radius); box-shadow:var(--shadow);">
        <label>
            Nome
            <input type="text" name="nome" required style="width:100%; padding:.7rem; border-radius:8px; border:1.5px solid #b9dfff;">
        </label>
        <label>
            E-mail
            <input type="email" name="email" required style="width:100%; padding:.7rem; border-radius:8px; border:1.5px solid #b9dfff;">
        </label>
        <label>
            Mensagem
            <textarea name="mensagem" rows="5" required style="width:100%; padding:.7rem; border-radius:8px; border:1.5px solid #b9dfff;"></textarea>
        </label>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
    <div id="contato-sucesso" style="display:none; margin-top:1rem; color:var(--brand); font-weight:600;">
        Obrigado pelo contato! Em breve retornaremos.
    </div>
</section>

<script>
document.getElementById('contato-form').addEventListener('submit', function(e){
    e.preventDefault();
    document.getElementById('contato-form').style.display = 'none';
    document.getElementById('contato-sucesso').style.display = 'block';
});
</script>
@endsection
