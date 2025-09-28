<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\CartItems;
use App\Models\Addresses;
use App\Models\Orders;
use App\Models\OrderItems;
class CartsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    /**
     * Lista todos os carrinhos (apenas admin).
     */
    public function index()
    {
        $this->authorizeAdmin();
        $carts = Carts::with('client')->get();
        return view('admin.carts.index', compact('carts'));
    }

    /**
     * Formulário para criar um carrinho (geralmente só sistema cria).
     */
    public function create()
    {
        $this->authorizeAdmin();
        $clients = Client::all();
        return view('admin.carts.create', compact('clients'));
    }

    /**
     * Salva novo carrinho.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_clients' => 'nullable|exists:clients,id',
            'session_id' => 'required|string|max:100|unique:carts,session_id',
        ]);

        Carts::create($request->all());

        return redirect()->route('admin.carts.index')->with('success', 'Carrinho criado com sucesso!');
    }

    /**
     * Mostra detalhes de um carrinho específico.
     */
    public function show(Carts $cart)
    {
        $cart->load(['client', 'items']);
        return view('admin.carts.show', compact('cart'));
    }

    /**
     * Formulário para editar um carrinho.
     */
    public function edit(Carts $cart)
    {
        $this->authorizeAdmin();
        $clients = Client::all();
        return view('admin.carts.edit', compact('cart', 'clients'));
    }

    /**
     * Atualiza carrinho.
     */
    public function update(Request $request, Carts $cart)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_clients' => 'nullable|exists:clients,id',
            'session_id' => 'required|string|max:100|unique:carts,session_id,' . $cart->id,
        ]);

        $cart->update($request->all());

        return redirect()->route('carts.index')->with('success', 'Carrinho atualizado com sucesso!');
    }

    /**
     * Remove carrinho.
     */
    public function destroy(Carts $cart)
    {
        $this->authorizeAdmin();
        $cart->delete();
        return redirect()->route('admin.carts.index')->with('success', 'Carrinho removido com sucesso!');
    }

    /**
     * Apenas admins podem manipular carrinhos diretamente.
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
/*
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

    // Cria o carrinho
    $cart = Carts::create([
        'id_clients' => $user->id,
        'session_id' => session()->getId(),
        'id_addresses' => $addressId // salva o endereço escolhido
    ]);

    // Cria os itens
    foreach ($items as $item) {
        CartItems::create([
            'id_carts'    => $cart->id,
            'id_products' => $item['id'],
            'quantity'    => $item['qty'],
            'price'       => $item['price'],
            'title'       => $item['title'],
            'session_id'  => $cart->session_id,
        ]);
    }

    return response()->json([
        'message' => 'Pedido finalizado com sucesso!',
        'cart_id' => $cart->id
    ]);
}

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

    // =========================
    // 1. Criar o carrinho
    // =========================
    $cart = Carts::create([
        'id_clients'   => $user->id,
        'session_id'   => session()->getId(),
        'id_addresses' => $addressId
    ]);

    // =========================
    // 2. Criar os itens no carrinho
    // =========================
    $total = 0;
    foreach ($items as $item) {
        CartItems::create([
            'id_carts'    => $cart->id,
            'id_products' => $item['id'],
            'quantity'    => $item['qty'],
            'price'       => $item['price'],
            'title'       => $item['title'],
            'session_id'  => $cart->session_id,
        ]);

        $total += $item['price'] * $item['qty'];
    }

    // =========================
    // 3. Criar o pedido (status pendente)
    // =========================
    $order = Orders::create([
        'id_clients'   => $user->id,
        'id_addresses' => $addressId,
        'status'       => 'pendente',
        'total_value'  => $total,
    ]);

    // =========================
    // 4. Criar os itens do pedido
    // =========================
    foreach ($items as $item) {
        $quantity = $item['quantity'] ?? $item['qty'] ?? 1;
        OrderItems::create([
            'id_order'     => $order->id,
            'id_product' => $item['id'],
            'id_variants'  => $item['variant_id'] ?? null, // se tiver variantes
            'title'       => $item['title'],
            'price'        => $item['price'],
            'quantity'     => $quantity,
        ]);
    }

    return response()->json([
        'message'  => 'Pedido finalizado com sucesso!',
        'cart_id'  => $cart->id,
        'order_id' => $order->id,
        'status'   => $order->status,
    ]);
}

public function showCart()
{
    $user = Auth::user();
    $addresses = Addresses::where('id_clients', $user->id)->get(); // pega todos
    return view('admin.cart', compact('addresses'));
}
}