@extends('layouts.app')

@section('title', 'Pedido Realizado')

@section('content')
<main class="pedido-sucesso" style="text-align:center; padding:2rem;">
    <h1>Pedido realizado com sucesso</h1>
    <div style="margin:2rem 0;">
        <span style="font-size:4rem; color:green;">✔️</span>
    </div>
    <p style="margin-bottom:1rem;">
        Muito obrigado!<br>
        Esperamos que aproveite seu pedido! Acompanhe o status enquanto aguarda.
    </p>

    @php
        $order = session('last_order');
        $whatsappMessage = null;

        if ($order) {
            // Nome do cliente
            $clientName = $order->client->full_name ?? 'Cliente';

            $message = "Olá Caiana. Me chamo {$clientName}, gostaria de informações sobre meu pedido #{$order->id}\n\n";

            foreach ($order->items as $item) {
                $message .= "- {$item->product->title} ({$item->quantity}x) R$" 
                          . number_format($item->price, 2, ',', '.') . "\n";
            }

            $message .= "\nTotal: R$" . number_format($order->total_value, 2, ',', '.') . "\n";

            // Informar cupom utilizado, se houver
           if (!empty($order->coupon_code)) {
    $message .= "Cupom utilizado: {$order->coupon_code}\n";
}


            if ($order->address) {
                $addr = $order->address;
                $message .= "\nEndereço de entrega: {$addr->road}, {$addr->number}";
                if ($addr->complement) $message .= " ({$addr->complement})";
                $message .= ", {$addr->neighborhood}, {$addr->city}/{$addr->state} - CEP: {$addr->cep}";
            }

            // Codifica a mensagem para URL
            $whatsappMessage = urlencode($message);
        }
    @endphp

    <div style="display:flex; flex-direction:column; gap:1rem; max-width:400px; margin:auto;">
        <a href="{{ route('meus.pedidos') }}" 
           style="background-color:red; color:white; padding:1rem; text-decoration:none; border-radius:8px;">
           Acompanhar pedido
        </a>

        @if($whatsappMessage)
            <a href="https://wa.me/5581981327731?text={{ $whatsappMessage }}" 
               style="border:2px solid green; color:green; padding:1rem; text-decoration:none; border-radius:8px;">
               Continuar no WhatsApp
            </a>
        @endif
    </div>
</main>

{{-- Limpa o carrinho do localStorage --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        localStorage.removeItem('cart');
    });
</script>
@endsection
