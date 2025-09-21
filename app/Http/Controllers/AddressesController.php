<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Listar todos os endereços
     */
    public function index()
    {
        if(Auth::user()->role === 'admin'){
            $addresses = Addresses::all();
        } else {
            $addresses = Addresses::where('id_clients', Auth::id())->get();
        }

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Mostrar formulário para criar novo endereço
     */
    public function create()
    {
        return view('addresses.create');
    }

    /**
     * Salvar endereço no banco
     */public function store(Request $request)
{
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

    $address = Addresses::create([
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

    // Se a requisição for AJAX, retorna JSON
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Endereço criado com sucesso!',
            'address' => $address
        ]);
    }

    // Para requisições normais
    return redirect()->back()->with('success', 'Endereço criado com sucesso.');
}

    /**
     * Mostrar detalhes de um endereço
     */
    public function show(Addresses $addresses)
    {
        $this->authorizeAddress($addresses);
        return view('addresses.show', compact('addresses'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Addresses $addresses)
    {
        $this->authorizeAddress($addresses);
        return view('addresses.edit', compact('addresses'));
    }

    /**
     * Atualizar endereço no banco
     */
    public function update(Request $request, Addresses $addresses)
    {
        $this->authorizeAddress($addresses);

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

        $addresses->update($request->only([
            'road', 'number', 'complement', 'referenc', 
            'neighborhood', 'city', 'state', 'cep', 'country'
        ]));

        return redirect()->route('addresses.index')->with('success', 'Endereço atualizado com sucesso.');
    }

    /**
     * Deletar endereço
     */
    public function destroy(Addresses $addresses)
    {
        $this->authorizeAdmin();
        $addresses->delete();
        return redirect()->route('addresses.index')->with('success', 'Endereço deletado com sucesso.');
    }

    /**
     * Verifica se o usuário logado pode acessar este endereço
     */
    private function authorizeAddress($addresses)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $addresses->id_clients !== $user->id) {
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
