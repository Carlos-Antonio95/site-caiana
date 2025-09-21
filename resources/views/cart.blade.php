@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Carrinho</h1>

    <div class="cart">
        <h2>Seu Carrinho</h2>
        <div id="cart-items"></div>

        <div class="subtotal">
            Total: <span id="subtotal">R$0,00</span>
        </div>

        {{-- Endereços --}}
        @if($addresses->isEmpty())
            <h3>Informe seu endereço</h3>
            <form id="address-form">
                <div>
                    <label>CEP</label>
                    <input type="text" name="cep" id="cep" required>
                    <button type="button" id="btn-cep">Buscar</button>
                </div>
                <div>
                    <label>Rua</label>
                    <input type="text" name="road" id="road" required>
                </div>
                <div>
                    <label>Número</label>
                    <input type="text" name="number" id="number" required>
                </div>
                <div>
                    <label>Complemento</label>
                    <input type="text" name="complement" id="complement">
                </div>
                <div>
                    <label>Bairro</label>
                    <input type="text" name="neighborhood" id="neighborhood" required>
                </div>
                <div>
                    <label>Cidade</label>
                    <input type="text" name="city" id="city" required>
                </div>
                <div>
                    <label>Estado</label>
                    <input type="text" name="state" id="state" required>
                </div>
                <div>
                    <label>País</label>
                    <input type="text" name="country" id="country" required value="Brasil">
                </div>
                <button type="submit">Salvar Endereço</button>
            </form>
        @else
            <h3>Selecione um endereço</h3>
            <form id="select-address-form">
                <select name="address_id" id="address_id" required>
                    @foreach($addresses as $addr)
                        <option value="{{ $addr->id }}">
                            {{ $addr->road }}, {{ $addr->number }}
                            - {{ $addr->neighborhood }}, {{ $addr->city }}/{{ $addr->state }}
                            - {{ $addr->cep }}
                        </option>
                    @endforeach
                </select>
                <button type="button" id="btn-change-address" class="btn btn-secondary">
                    Usar outro endereço
                </button>
            </form>

            {{-- Formulário escondido para cadastrar novo endereço --}}
            <form id="address-form" style="display:none; margin-top:15px;">
                <div>
                    <label>CEP</label>
                    <input type="text" name="cep" id="cep" required>
                    <button type="button" id="btn-cep">Buscar</button>
                </div>
                <div>
                    <label>Rua</label>
                    <input type="text" name="road" id="road" required>
                </div>
                <div>
                    <label>Número</label>
                    <input type="text" name="number" id="number" required>
                </div>
                <div>
                    <label>Complemento</label>
                    <input type="text" name="complement" id="complement">
                </div>
                <div>
                    <label>Bairro</label>
                    <input type="text" name="neighborhood" id="neighborhood" required>
                </div>
                <div>
                    <label>Cidade</label>
                    <input type="text" name="city" id="city" required>
                </div>
                <div>
                    <label>Estado</label>
                    <input type="text" name="state" id="state" required>
                </div>
                <div>
                    <label>País</label>
                    <input type="text" name="country" id="country" required value="Brasil">
                </div>
                <button type="submit">Salvar Novo Endereço</button>
            </form>
        @endif

        <button id="checkout" class="btn btn-primary">Finalizar Pedido</button>
    </div>
</div>

<!-- CSRF Token obrigatório para POST Laravel -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@vite(['resources/js/app.js', 'resources/js/cart.js'])
@endsection
