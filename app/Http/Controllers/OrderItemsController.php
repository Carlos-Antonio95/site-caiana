<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\OrderItems; 
use App\Models\Orders;
use App\Models\Products_Variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderItemsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar itens de pedidos
     * Admin vê todos, cliente só vê seus pedidos
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $items = OrderItems::with(['order', 'variant'])->get();
        } else {
            $items = OrderItems::with(['order', 'variant'])
                ->whereHas('order', function ($q) use ($user) {
                    $q->where('id_clients', $user->id);
                })
                ->get();
        }

        return view('order_items.index', compact('items'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $this->authorizeAdmin();

        $orders = Orders::all();
        $variants = Products_Variants::all();

        return view('order_items.create', compact('orders', 'variants'));
    }

    /**
     * Salvar item no banco
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_order' => 'required|exists:orders,id',
            'id_variants' => 'required|exists:product_variants,id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        OrderItems::create($request->all());

        return redirect()->route('order_items.index')->with('success', 'Item adicionado ao pedido!');
    }

    /**
     * Mostrar detalhes
     */
    public function show(OrderItems $orderItem)
    {
        $this->authorizeClient($orderItem);

        return view('order_items.show', compact('orderItem'));
    }

    /**
     * Editar item
     */
    public function edit(OrderItems $orderItem)
    {
        $this->authorizeAdmin();

        $orders = Orders::all();
        $variants = Products_Variants::all();

        return view('order_items.edit', compact('orderItem', 'orders', 'variants'));
    }

    /**
     * Atualizar item
     */
    public function update(Request $request, OrderItems $orderItem)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_order' => 'required|exists:orders,id',
            'id_variants' => 'required|exists:product_variants,id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $orderItem->update($request->all());

        return redirect()->route('order_items.index')->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Excluir item
     */
    public function destroy(OrderItems $orderItem)
    {
        $this->authorizeAdmin();

        $orderItem->delete();

        return redirect()->route('order_items.index')->with('success', 'Item removido com sucesso!');
    }

    /**
     * Apenas admin pode criar/editar/excluir
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }

    /**
     * Cliente só pode visualizar seus próprios pedidos
     */
    private function authorizeClient(OrderItems $orderItem)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $orderItem->order->id_clients !== $user->id) {
            abort(403, 'Acesso negado! Você não pode acessar este item.');
        }
    }
}