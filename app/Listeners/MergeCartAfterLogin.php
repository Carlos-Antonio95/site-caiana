<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use App\Models\Carts;

class MergeCartAfterLogin
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Authenticated  $event
     * @return void
     */
    public function handle(Authenticated $event)
    {
        // Usuários sem cadastro de cliente (ex.: admin) não têm carrinho
        $clientId = $event->user->client?->id;
        if (! $clientId) {
            return;
        }

        // Associa ao cliente os carrinhos criados nesta sessão
        Carts::where('session_id', session()->getId())
            ->update(['id_clients' => $clientId]);
    }
}
