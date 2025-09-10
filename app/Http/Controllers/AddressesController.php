<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressesController extends Controller
{
    // Middleware para proteger rotas: só usuários logados podem acessar
    public function __construct() {
        $this->middleware('auth');
    }
    
     /**
     * Listar todos os endereços
     * Admin vê todos, cliente pode ver apenas o próprio endeço
     */
    public function index()
    {

        if(Auth::user()->role === 'admin'){
            $addresses = Addresses::all();
        } else {
            $addresses = Addresses::where('id_users', Auth::id())->get();
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
     */
    public function store(Request $request)
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

        Addresses::create([
            'id_users' => Auth::id(),
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

        return redirect()->route('addresses.index')->with('success', 'Endereço criado com sucesso.');
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

        $addresses->update([
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

        return redirect()->route('addresses.index')->with('success', 'Endereço atualizado com sucesso.');
    }

    /**
     * Deletar endereço do banco
     */
    public function destroy(Addresses $addresses)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem deletar clientes.');
        }

        $addresses->delete();

        return redirect()->route('addresses.index')->with('success', 'Endereço deletado com sucesso.');
    }

    
    /**
     * Verifica se o usuário logado pode acessar este endereço
     */
    private function authorizeAddress($addresses)
    {
        $user = Auth::user();

        // Admin pode acessar todos, cliente apenas o próprio
        if ($user->role !== 'admin' && $addresses->id_users !== $user->id) {
            abort(403, 'Acesso negado! Você não pode acessar este endereço.');
        }
    }

    /**
     * Função para verificar se o usuário logado é admin.
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}
