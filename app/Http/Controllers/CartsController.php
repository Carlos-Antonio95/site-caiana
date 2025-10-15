<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\CartItems;
use App\Models\Client;
use App\Models\Addresses;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todos os carrinhos (admin)
     */
    public function index()
    {
        $this->authorizeAdmin();
        $carts = Carts::with(['client', 'items'])->get();
        return view('admin.carts.index', compact('carts'));
    }

    /**
     * Criar um novo carrinho (admin)
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
            'cart'    => $cart,
        ]);
    }

    /**
     * Atualizar carrinho
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
            'cart'    => $cart,
        ]);
    }

    /**
     * Remover carrinho
     */
    public function destroy(Carts $cart)
    {
        $this->authorizeAdmin();
        $cart->delete();

        return response()->json(['message' => 'Carrinho removido com sucesso!']);
    }

    private function localStorageRemoveCartScript()
{
    echo "<script>localStorage.removeItem('cart');</script>";
}
    /**
     * Finalizar checkout (gera pedido)
     */

    /**
     * Finalizar checkout (gera pedido)
     */
    /*
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $items = $request->input('items', []);
        $addressId = $request->input('address_id');

        // Desconto salvo da sessão (cupom aplicado)
        $discount = session('discount', 0);

        if (empty($items)) {
            return response()->json(['message' => 'Carrinho vazio'], 400);
        }

        if (!$addressId || !Addresses::where('id_clients', $user->id)->where('id', $addressId)->exists()) {
            return response()->json(['message' => 'Endereço inválido'], 400);
        }

        $cart = Carts::create([
            'id_clients'   => $user->id,
            'session_id'   => session()->getId(),
            'id_addresses' => $addressId,
        ]);

        $total = 0;

        foreach ($items as $item) {
            $product = Products::find($item['id']);
            if (!$product) continue;

            $price = $product->final_price;

            CartItems::create([
                'id_carts'    => $cart->id,
                'id_products' => $product->id,
                'quantity'    => $item['qty'],
                'price'       => $price,
                'title'       => $product->title,
                'session_id'  => $cart->session_id,
            ]);

            $total += $price * $item['qty'];
        }

        // Aplica o desconto armazenado
        $finalTotal = max(0, $total - $discount);

        $order = Orders::create([
            'id_clients'   => $user->id,
            'id_addresses' => $addressId,
            'status'       => 'pendente',
            'total_value'  => $finalTotal,
            'discount'     => $discount,
        ]);

        // Limpa a sessão do desconto após criar o pedido
        session()->forget('discount');

        foreach ($items as $item) {
            $product = Products::find($item['id']);
            if (!$product) continue;

            OrderItems::create([
                'id_order'  => $order->id,
                'id_product'=> $product->id,
                'title'     => $product->title,
                'price'     => $product->final_price,
                'quantity'  => $item['qty'],
            ]);
        }

        return response()->json([
            'message'   => 'Pedido finalizado com sucesso!',
            'order_id'  => $order->id,
            'status'    => $order->status,
        ]);
    }
        
*/
/**
 */// Finalizar checkout (gera pedido)
public function checkout(Request $request)
{
    $user = Auth::user();
    $items = $request->input('items', []);
    $addressId = $request->input('address_id');

    $discount = session('discount', 0);
    $couponCode = session('applied_coupon') ?? null;

    if (empty($items)) {
        return response()->json(['success' => false, 'message' => 'Carrinho vazio'], 400);
    }

    // 🔹 Pega o cliente do usuário logado
    $client = $user->client;
    if (!$client) {
        return response()->json(['success' => false, 'message' => 'Cliente não encontrado'], 400);
    }

    // 🔹 Busca o endereço pelo id do cliente
    $address = Addresses::where('id_clients', $client->id)
                        ->where('id', $addressId)
                        ->first();
    if (!$address) {
        return response()->json(['success' => false, 'message' => 'Endereço inválido'], 400);
    }

    // 🔹 Cria o carrinho
    $cart = Carts::create([
        'id_clients'   => $client->id,
        'session_id'   => session()->getId(),
        'id_addresses' => $addressId,
    ]);

    $total = 0;

    // 🔹 Cria os itens do carrinho
    foreach ($items as $item) {
        $product = Products::find($item['id']);
        if (!$product) continue;

        CartItems::create([
            'id_carts'    => $cart->id,
            'id_products' => $product->id,
            'quantity'    => $item['qty'],
            'price'       => $product->final_price,
            'title'       => $product->title,
            'session_id'  => $cart->session_id,
        ]);

        $total += $product->final_price * $item['qty'];
    }

    // 🔹 Aplica o desconto
    $finalTotal = max(0, $total - $discount);

    // 🔹 Cria o pedido
    $order = Orders::create([
        'id_clients'   => $client->id,
        'id_addresses' => $addressId,
        'status'       => 'pendente',
        'total_value'  => $finalTotal,
        'discount'     => $discount,
    ]);

    // 🔹 Cria os itens do pedido
    foreach ($items as $item) {
        $product = Products::find($item['id']);
        if (!$product) continue;

        OrderItems::create([
            'id_order'   => $order->id,
            'id_product' => $product->id,
            'title'      => $product->title,
            'price'      => $product->final_price,
            'quantity'   => $item['qty'],
        ]);
    }

    // 🔹 Limpa sessões e salva pedido
    session()->forget('discount');
    session(['last_order' => $order]);

    return response()->json([
        'success'   => true,
        'order_id'  => $order->id,
        'redirect'  => route('sucesso'),
    ]);
}
    /**
     * Editar carrinho (admin)
     */
    public function edit(Carts $cart)
    {
        $this->authorizeAdmin();

        $cart->load(['items']);
        $clients = Client::all();

        return view('admin.carts.edit', compact('cart', 'clients'));
    }

    /**
     * Mostrar carrinho do usuário autenticado
     */
     /*
  //  public function showCart()
//    {
        //$user = Auth::user();
        //$addresses = Addresses::where('id_clients', $user->id)->get();
       // $cart = Carts::with('items.product')->where('id_clients', $user->id)->first();

        return view('cart', compact('addresses', 'cart'));
    }*/
    public function showCart()
{
    $user = Auth::user();

    // Pega o cliente relacionado ao usuário
    $client = $user->client;

    // Se existir cliente, pega os endereços; se não, retorna coleção vazia
    $addresses = $client ? $client->addresses()->get() : collect();

    // Pega o carrinho do cliente (opcional: ou pela sessão)
    $cart = Carts::with('items.product')->where('id_clients', $client ? $client->id : 0)->first();

    return view('cart', compact('addresses', 'cart'));
}

    /**
     * Mostrar detalhes de um carrinho (admin)
     */
    public function show(Carts $cart)
    {
        $this->authorizeAdmin();
        $cart->load(['client', 'items.product']);
        return view('admin.carts.show', compact('cart'));
    }

    /**
     * Verifica se usuário é admin
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}
