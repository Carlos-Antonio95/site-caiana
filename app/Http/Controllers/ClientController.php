<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Middleware para proteger rotas: só usuários logados podem acessar
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todos os clientes
     * Admin vê todos, cliente pode ver apenas o próprio cadastro
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $clients = Client::all();
        } else {
            $clients = Client::where('id_users', $user->id)->get();
        }

        return view('clients.index', compact('clients'));
    }

    /**
     * Mostrar formulário para criar novo cliente
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Salvar cliente no banco
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:200',
            'phone' => 'required|string|max:20',
            'date_birth' => 'required|date',
        ]);

        Client::create([
            'id_users' => Auth::id(),
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'date_birth' => $request->date_birth,
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso!');
    }

    /**
     * Mostrar detalhes de um cliente
     */
    public function show(Client $client)
    {
        $this->authorizeClient($client);

        return view('clients.show', compact('client'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Client $client)
    {
        $this->authorizeClient($client);

        return view('clients.edit', compact('client'));
    }

    /**
     * Atualizar cliente no banco
     */
    public function update(Request $request, Client $client)
    {
        $this->authorizeClient($client);

        $request->validate([
            'full_name' => 'required|string|max:200',
            'phone' => 'required|string|max:20',
            'date_birth' => 'required|date',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Deletar cliente
     * Apenas admin pode deletar
     */
    public function destroy(Client $client)
    {
        $this->authorizeAdmin();

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente excluído com sucesso!');
    }

    /**
     * Verifica se o usuário logado pode acessar este cliente
     */
    private function authorizeClient(Client $client)
    {
        $user = Auth::user();

        // Admin pode acessar todos, cliente apenas o próprio
        if ($user->role !== 'admin' && $client->id_users !== $user->id) {
            abort(403, 'Acesso negado! Você não pode acessar este cliente.');
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
