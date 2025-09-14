<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
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
     * Formulário para adicionar item a um carrinho.
     */
    public function create()
    {
        $this->authorizeAdmin();
        $carts = Carts::all();
        $products = Products::all();
        return view('cart_items.create', compact('carts', 'products'));
    }

    /**
     * Salva novo item no carrinho.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_carts' => 'required|exists:carts,id',
            'id_products' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        CartItems::create($request->all());

        return redirect()->route('cart_items.index')->with('success', 'Item adicionado ao carrinho com sucesso!');
    }

    /**
     * Mostra detalhes de um item do carrinho.
     */
    public function show(CartItems $cartItem)
    {
        $cartItem->load(['cart', 'product']);
        return view('cart_items.show', compact('cartItem'));
    }

    /**
     * Formulário para editar item do carrinho.
     */
    public function edit(CartItems $cartItem)
    {
        $this->authorizeAdmin();
        $carts = Carts::all();
        $products = Products::all();
        return view('cart_items.edit', compact('cartItem', 'carts', 'products'));
    }

    /**
     * Atualiza item do carrinho.
     */
    public function update(Request $request, CartItems $cartItem)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_carts' => 'required|exists:carts,id',
            'id_products' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $cartItem->update($request->all());

        return redirect()->route('cart_items.index')->with('success', 'Item do carrinho atualizado com sucesso!');
    }

    /**
     * Remove item do carrinho.
     */
    public function destroy(CartItems $cartItem)
    {
        $this->authorizeAdmin();
        $cartItem->delete();
        return redirect()->route('cart_items.index')->with('success', 'Item do carrinho removido com sucesso!');
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
