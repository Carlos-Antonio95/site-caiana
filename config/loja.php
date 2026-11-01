<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Contatos da loja
    |--------------------------------------------------------------------------
    |
    | WhatsApp só com dígitos (DDI + DDD + número), usado nos links wa.me.
    | Vazio = links escondidos.
    |
    */

    'whatsapp_number' => preg_replace('/\D/', '', (string) env('WHATSAPP_NUMBER', '')),
    'instagram_url' => env('INSTAGRAM_URL'),

    /*
    |--------------------------------------------------------------------------
    | Modo demonstração
    |--------------------------------------------------------------------------
    |
    | Mostra a faixa "Loja demonstrativa — pagamento simulado" no topo.
    |
    */

    'demo' => (bool) env('LOJA_DEMO', true),

];
