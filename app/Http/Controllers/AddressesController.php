<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressesController extends Controller
{
    // Aplica middleware 'auth' a todos os métodos
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Listar todos os endereços
     */
    public function index()
    {
        // Se for admin, mostra todos os endereços
        if (Auth::user()->role === 'admin') {
            $addresses = Addresses::all();
        } else {
            // Caso contrário, mostra apenas os do usuário logado
            $addresses = Addresses::where('id_clients', Auth::id())->get();
        }

        // Passa os endereços para a view
        return view('admin.addresses.index', compact('addresses'));
    }

    /**
     * Mostrar formulário para criar novo endereço
     */
    public function create()
    {
        return view('admin.addresses.create');
    }

    /**
     * Salvar endereço no banco
     */
    public function store(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'road' => 'required|string|max:200',
            'number' => 'required|string|max:10',
            'complement' => 'nullable|string|max:50',
            'referenc' => 'nullable|string|max:200',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'cep' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        // Cria o endereço no banco
        $addresses = Addresses::create([
            'id_clients' => Auth::id(),
            'road' => $request->road,
            'number' => $request->number,
            'complement' => $request->complement,
            'referenc' => $request->referenc,
            'neighborhood' => $request->neighborhood,
            'city' => $request->city,
            'state' => $request->state,
            'cep' => $request->cep,
            'country' => $request->country,
        ]);

        // Retorna JSON se for requisição AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Endereço criado com sucesso!',
                'addresses' => $addresses
            ]);
        }

        // Redireciona para a mesma página com mensagem de sucesso
        return redirect()->back()->with('success', 'Endereço criado com sucesso.');
    }

    /**
     * Mostrar detalhes de um endereço
     */
    public function show(Addresses $address)
    {
        // Autoriza o acesso
        $this->authorizeAddress($address);

        // Passa a instância para a view
        return view('admin.addresses.show', ['addresses' => $address]);
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Addresses $address)
    {
        // Autoriza o acesso
        $this->authorizeAddress($address);

        // Passa a instância para a view
        return view('admin.addresses.edit', ['addresses' => $address]);
    }

    /**
     * Atualizar endereço no banco
     */
    public function update(Request $request, Addresses $address)
    {
        $this->authorizeAddress($address);

        $request->validate([
            'road' => 'required|string|max:200',
            'number' => 'required|string|max:10',
            'complement' => 'nullable|string|max:50',
            'referenc' => 'nullable|string|max:200',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'cep' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        // Atualiza apenas os campos permitidos
        $address->update($request->only([
            'road', 'number', 'complement', 'referenc', 
            'neighborhood', 'city', 'state', 'cep', 'country'
        ]));

        return redirect()->route('admin.addresses.index')->with('success', 'Endereço atualizado com sucesso.');
    }

    /**
     * Deletar endereço
     */
    public function destroy(Addresses $address)
    {
        // Apenas admins podem deletar
        $this->authorizeAdmin();

        $address->delete();

        return redirect()->route('admin.addresses.index')->with('success', 'Endereço deletado com sucesso.');
    }

    /**
     * Verifica se o usuário logado pode acessar este endereço
     */
    private function authorizeAddress($address)
    {
        $user = Auth::user();

        // Se não for admin e o endereço não for do usuário logado, bloqueia
        if ($user->role !== 'admin' && $address->id_clients !== $user->id) {
            abort(403, 'Acesso negado! Você não pode acessar este endereço.');
        }
    }

    /**
     * Verifica se o usuário logado é admin
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}
