<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use App\Models\Carts;

class MergeCartAfterLogin
{
    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        $user = $event->user;

        // Busca carrinho temporário dessa sessão
        $sessionCart = Carts::where('session_id', session()->getId())->get();

        foreach ($sessionCart as $item) {
            $item->update([
                'user_id' => $user->id,
                'session_id' => null
            ]);
        }
    }
}
