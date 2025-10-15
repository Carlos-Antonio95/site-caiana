<?php

namespace App\Http\Controllers;

use App\Models\CartItems;
use App\Models\Carts;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista todos os itens de todos os carrinhos (apenas admin).
     */
    public function index()
    {
        $this->authorizeAdmin();
        $items = CartItems::with(['cart', 'product'])->get();
        return view('cart_items.index', compact('items'));
    }

    /**
     * Adiciona item ao carrinho via AJAX/Livewire.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'id_carts'    => 'required|exists:carts,id',
            'id_products' => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
        ]);

        $item = CartItems::create($validated);

        return response()->json([
            'message' => 'Item adicionado ao carrinho com sucesso!',
            'item'    => $item->load(['cart', 'product']),
        ]);
    }

    /**
     * Atualiza item do carrinho via AJAX/Livewire.
     */
    public function update(Request $request, CartItems $cartItem)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'id_carts'    => 'required|exists:carts,id',
            'id_products' => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
        ]);

        $cartItem->update($validated);

        return response()->json([
            'message' => 'Item do carrinho atualizado com sucesso!',
            'item'    => $cartItem->load(['cart', 'product']),
        ]);
    }

    /**
     * Remove item do carrinho via AJAX/Livewire.
     */
    public function destroy(CartItems $cartItem)
    {
        $this->authorizeAdmin();
        $cartItem->delete();

        return response()->json([
            'message' => 'Item do carrinho removido com sucesso!',
        ]);
    }

    /**
     * Apenas admins podem manipular diretamente os itens dos carrinhos.
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}
