@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Alterar Status do Pedido</h1>

    {{-- Formulário para atualizar status do pedido --}}
    <form action="{{ url('/test/orders/9/status') }}" method="POST">
        @csrf {{-- Token CSRF obrigatório para POST --}}
        
        <label for="status">Novo Status:</label>
        <select name="status" id="status">
            <option value="pendente">Pendente</option>
            <option value="aprovado">Aprovado</option>
            <option value="pago">Pago</option>
            <option value="enviado">Enviado</option>
            <option value="entregue">Entregue</option>
            <option value="cancelado">Cancelado</option>
        </select>

        <br><br>
        <button type="submit" class="btn btn-primary">Atualizar Status</button>
    </form>
</div>
@endsection
