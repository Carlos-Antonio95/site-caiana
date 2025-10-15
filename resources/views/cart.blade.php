@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Carrinho</h1>

    <div class="cart">
        <h2>Seu Carrinho</h2>
        <div id="cart-items"></div>

        {{-- Totais e Cupom --}}
        <div class="totais">
            <div class="subtotal">
                <p>Subtotal: <span id="subtotal">R$0,00</span></p>
                <p id="discount-area" style="display:none; color:green;">
                    Desconto: <span id="discount-value">R$0,00</span>
                </p>
                <p><strong>Total: <span id="total">R$0,00</span></strong></p>
            </div>

            <div class="coupon mt-3">
                <h4>Aplicar Cupom</h4>
                <input type="text" id="coupon-code" placeholder="Digite seu cupom" />
                <button type="button" id="apply-coupon" class="btn btn-secondary">Aplicar</button>
                <p id="coupon-message" style="color: green; display: none;"></p>
            </div>
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
                <button type="submit" class="btn btn-success">Salvar Endereço</button>
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
                <button type="button" id="btn-change-address" class="btn btn-secondary mt-2">
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
                <button type="submit" class="btn btn-success">Salvar Novo Endereço</button>
            </form>
        @endif

        <div class="mt-4">
            <button id="btn-finalizar" class="btn btn-primary">Finalizar Pedido</button>
        </div>
    </div>
</div>

<!-- CSRF Token obrigatório para POST Laravel -->
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Script para finalizar pedido --}}
<script>
document.getElementById('btn-finalizar')?.addEventListener('click', async () => {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (!cart.length) return alert('Seu carrinho está vazio');

    const addressIdEl = document.getElementById('address_id');
    const addressId = addressIdEl ? addressIdEl.value : null;
    if (!addressId) return alert('Selecione ou preencha um endereço');

    try {
        const res = await fetch('/cart/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ items: cart, address_id: addressId })
        });

        const data = await res.json();

        if (res.ok && data.success) {
            localStorage.removeItem('cart'); // limpa carrinho
            // Redireciona para a página de sucesso
            window.location.href = data.redirect;
        } else {
            alert(data.message || 'Erro ao finalizar pedido');
        }
    } catch(err) {
        console.error(err);
        alert('Erro ao finalizar pedido');
    }
});
</script>
@endsection