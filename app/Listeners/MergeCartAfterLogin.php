<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        $user = $event->user;

        // Pega o ID da sessão atual ou gera um ID único se não existir
        $sessionId = session()->getId() ?: uniqid();

        // Atualiza os carrinhos que pertencem a essa sessão
        Carts::where('session_id', $sessionId)
            ->update([
                'id_clients' => $user->client->id,
                'session_id' => $sessionId,
            ]);
    }
}
