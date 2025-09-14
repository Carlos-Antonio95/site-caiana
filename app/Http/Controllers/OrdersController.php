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

        return view('orders.index', compact('orders'));
    }

    /**
     * Formulário de criação de pedido
     */
    public function create()
    {
        $this->authorizeAdmin();

        $clients = Client::all();
        $addresses = Addresses::all();
        return view('orders.create', compact('clients', 'addresses'));
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
            'status' => 'required|in:pendente,pago,enviado,entregue,cancelado',
            'total_value' => 'required|numeric|min:0',
        ]);

        Orders::create($request->all());

        return redirect()->route('orders.index')->with('success', 'Pedido criado com sucesso!');
    }

    /**
     * Exibir pedido
     */
    public function show(Orders $order)
    {
        $this->authorizeClient($order);

        $order->load(['client', 'address', 'items']);
        return view('orders.show', compact('order'));
    }

    /**
     * Formulário para editar pedido
     */
    public function edit(Orders $order)
    {
        $this->authorizeAdmin();

        $clients = Client::all();
        $addresses = Addresses::all();
        return view('orders.edit', compact('order', 'clients', 'addresses'));
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
            'status' => 'required|in:pendente,pago,enviado,entregue,cancelado',
            'total_value' => 'required|numeric|min:0',
        ]);

        $order->update($request->all());

        return redirect()->route('orders.index')->with('success', 'Pedido atualizado com sucesso!');
    }

    /**
     * Excluir pedido
     */
    public function destroy(Orders $order)
    {
        $this->authorizeAdmin();

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Pedido excluído com sucesso!');
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
}