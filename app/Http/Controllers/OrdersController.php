<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Addresses;

class OrdersController  extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar pedidos.
     * Admin vê todos, cliente vê apenas os seus.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $orders = Orders::with(['client', 'address'])->get();
        } else {
            $orders = Orders::with(['client', 'address'])
                ->where('id_clients', $user->id)
                ->get();
        }

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Formulário de criação de pedido
     */
    public function create()
    {
        $this->authorizeAdmin();

        $clients = Client::all();
        $addresses = Addresses::all();
        return view('admin.orders.create', compact('clients', 'addresses'));
    }

    /**
     * Salvar novo pedido
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_clients' => 'required|exists:clients,id',
            'id_addresses' => 'required|exists:addresses,id',
            'status' => 'required|in:pendente,provado,pago,enviado,entregue,cancelado',
            'total_value' => 'required|numeric|min:0',
        ]);

        Orders::create($request->all());

        return redirect()->route('admin.orders.index')->with('success', 'Pedido criado com sucesso!');
    }

    /**
     * Exibir pedido
     */
    public function show(Orders $order)
    {
        $this->authorizeClient($order);

        $order->load(['client', 'address', 'items']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Formulário para editar pedido
     */
    public function edit(Orders $order)
    {
        $this->authorizeAdmin();

        $clients = Client::all();
        $addresses = Addresses::all();
        return view('admin.orders.edit', compact('order', 'clients', 'addresses'));
    }

    /**
     * Atualizar pedido
     */
    public function update(Request $request, Orders $order)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_clients' => 'required|exists:clients,id',
            'id_addresses' => 'required|exists:addresses,id',
            'status' => 'required|in:pendente,aprovado,pago,enviado,entregue,cancelado',
            'total_value' => 'required|numeric|min:0',
        ]);

        $order->update($request->all());

        return redirect()->route('adminorders.index')->with('success', 'Pedido atualizado com sucesso!');
    }

    /**
     * Excluir pedido
     */
    public function destroy(Orders $order)
    {
        $this->authorizeAdmin();

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pedido excluído com sucesso!');
    }

    /**
     * Somente admin pode manipular pedidos diretamente.
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }

    /**
     * Verifica se o cliente logado pode acessar o pedido.
     */
    private function authorizeClient(Orders $order)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $order->id_clients !== $user->id) {
            abort(403, 'Acesso negado! Você não pode acessar este pedido.');
        }
    }
/*
 public function changeStatus(Request $request, Orders $order)
{
    $this->authorizeAdmin();

    $request->validate([
        'status' => 'required|in:pendente,pago,aprovado,enviado,entregue,cancelado',
    ]);

    $newStatus = $request->input('status');

    // Decrementa estoque apenas na primeira vez que for aprovado
    if ($newStatus === 'aprovado' && !$order->stock_decremented) {
        foreach ($order->items as $item) {
            $product = $item->product;
            $product->quantity -= $item->quantity;
            $product->save();
        }
        $order->stock_decremented = true;
    }

    // Restaurar estoque se pedido for cancelado e estoque já foi decrementado
    if ($newStatus === 'cancelado' && $order->stock_decremented) {
        foreach ($order->items as $item) {
            $product = $item->product;
            $product->quantity += $item->quantity;
            $product->save();
        }
        $order->stock_decremented = false;
    }

    $order->status = $newStatus;
    $order->save();

    return redirect()->route('orders.show', $order)->with('success', 'Status atualizado com sucesso!');
}*/public function changeStatusTest(Request $request, Orders $order)
{
    // Validação do status
    $request->validate([
        'status' => 'required|in:pendente,pago,aprovado,enviado,entregue,cancelado',
    ]);

    $newStatus = $request->input('status');

    // Decrementa estoque se status mudou para 'aprovado' e ainda não foi decrementado
    if (($newStatus === 'aprovado' || $newStatus === 'enviado' || $newStatus === 'entregue' || $newStatus === 'pago') && !$order->stock_decremented) {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->stock_quantity -= $item->quantity;
                // Garante que estoque não fique negativo
                if ($product->stock_quantity <= 0) {
                    $product->stock_quantity = 0;
                }
                if ($product->stock_quantity == 0) {
                    $product->status = 'inativo'; // Marca como inativo se estoque zerar
                }
                $product->save();
            }
        }
        $order->stock_decremented = true;
    }

    // Restaura estoque se status mudou para 'cancelado' e estoque já foi decrementado
    if ($newStatus === 'cancelado' && $order->stock_decremented) {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->stock_quantity += $item->quantity;
                $product->save();
            }
        }
        $order->stock_decremented = false;
    }

    // Atualiza status do pedido
    $order->status = $newStatus;
    $order->save();

    return response()->json([
        'success' => true,
        'order_id' => $order->id,
        'new_status' => $order->status,
        'stock_decremented' => $order->stock_decremented
    ]);
}

}