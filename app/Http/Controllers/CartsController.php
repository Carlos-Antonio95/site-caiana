<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\CartItems;
use App\Models\Client;
use App\Models\Addresses;
use App\Models\Orders;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todos os carrinhos (admin).
     */
    public function index()
    {
        $this->authorizeAdmin();
        $carts = Carts::with(['client', 'items'])->get();
        return view('admin.carts.index', compact('carts'));
    }

    /**
     * Criar um carrinho (apenas admin ou sistema).
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_clients' => 'nullable|exists:clients,id',
            'session_id' => 'required|string|max:100|unique:carts,session_id',
        ]);

        $cart = Carts::create($request->only('id_clients', 'session_id'));

        return response()->json([
            'message' => 'Carrinho criado com sucesso!',
            'cart' => $cart
        ]);
    }

    /**
     * Atualizar carrinho.
     */
    public function update(Request $request, Carts $cart)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_clients' => 'nullable|exists:clients,id',
            'session_id' => 'required|string|max:100|unique:carts,session_id,' . $cart->id,
        ]);

        $cart->update($request->only('id_clients', 'session_id'));

        return response()->json([
            'message' => 'Carrinho atualizado com sucesso!',
            'cart' => $cart
        ]);
    }

    /**
     * Remover carrinho.
     */
    public function destroy(Carts $cart)
    {
        $this->authorizeAdmin();
        $cart->delete();

        return response()->json([
            'message' => 'Carrinho removido com sucesso!'
        ]);
    }

    /**
     * Checkout do carrinho.
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $items = $request->input('items', []);
        $addressId = $request->input('address_id');

        if (empty($items)) {
            return response()->json(['message' => 'Carrinho vazio'], 400);
        }

        if (!$addressId || !Addresses::where('id_clients', $user->id)->where('id', $addressId)->exists()) {
            return response()->json(['message' => 'Endereço inválido'], 400);
        }

        $cart = Carts::create([
            'id_clients' => $user->id,
            'session_id' => session()->getId(),
            'id_addresses' => $addressId
        ]);

        $total = 0;
        foreach ($items as $item) {
            CartItems::create([
                'id_carts' => $cart->id,
                'id_products' => $item['id'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'title' => $item['title'],
                'session_id' => $cart->session_id,
            ]);

            $total += $item['price'] * $item['qty'];
        }

        $order = Orders::create([
            'id_clients' => $user->id,
            'id_addresses' => $addressId,
            'status' => 'pendente',
            'total_value' => $total,
        ]);

        foreach ($items as $item) {
            $quantity = $item['quantity'] ?? $item['qty'] ?? 1;
            OrderItems::create([
                'id_order' => $order->id,
                'id_product' => $item['id'],
                'id_variants' => $item['variant_id'] ?? null,
                'title' => $item['title'],
                'price' => $item['price'],
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'message' => 'Pedido finalizado com sucesso!',
            'cart_id' => $cart->id,
            'order_id' => $order->id,
            'status' => $order->status,
        ]);
    }

    /**
 * Editar carrinho (apenas admin)
*/

public function edit(Carts $cart)
{
    $this->authorizeAdmin();

    $cart->load(['items']); // já carrega os itens do carrinho

    // Carrega todos os clientes para popular o select
    $clients = Client::all();

    return view('admin.carts.edit', compact('cart', 'clients'));
}


    /**
     * Mostrar carrinho do usuário.
     */
    public function showCart()
{
    $user = Auth::user();
    $addresses = Addresses::where('id_clients', $user->id)->get();

    // opcional: carregar os itens do carrinho do usuário
    $cart = Carts::with('items.product')->where('id_clients', $user->id)->first();

    return view('cart', compact('addresses', 'cart'));
}

    /**
 * Mostrar detalhes de um carrinho específico (admin)
 */
public function show(Carts $cart)
{
    $this->authorizeAdmin();

    // Carrega os itens do carrinho e cliente
    $cart->load(['client', 'items.product']); // assumindo relação 'product' em CartItems

    return view('admin.carts.show', compact('cart'));
}


    /**
     * Apenas admins podem manipular diretamente os carrinhos.
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}
