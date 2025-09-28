<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Middleware para proteger rotas
     public function __construct()
    {
        // Middleware que protege todas as rotas do controller
        $this->middleware('auth');
    }

    /**
     * Listar todos os usuários.
     * Apenas admin pode acessar.
     */
    public function index()
    {
        $this->authorizeAdmin();

        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Mostrar formulário para criar novo usuário.
     * Apenas admin.
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('users.create');
    }

    /**
     * Salvar usuário no banco.
     * Apenas admin.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:cliente,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Mostrar detalhes de um usuário.
     * Admin vê todos, cliente só vê a si mesmo.
     */
    public function show(User $user)
    {
        $this->authorizeUser($user);

        return view('users.show', compact('user'));
    }

    /**
     * Mostrar formulário de edição.
     * Admin pode editar qualquer um, cliente só a si mesmo.
     */
    public function edit(User $user)
    {
        $this->authorizeUser($user);

        return view('users.edit', compact('user'));
    }

    /**
     * sdjadjsadaskdl
     * Atualizar usuário no banco.
     */
    public function update(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:cliente,admin',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Deletar usuário.
     * Apenas admin.
     */
    public function destroy(User $user)
    {
        $this->authorizeAdmin();
        // Impede que um admin se delete acidentalmente
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Você não pode deletar a si mesmo.');
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
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

    /**
     * Função para verificar se o usuário logado pode acessar este registro.
     */
    private function authorizeUser(User $user)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $user->id) {
            abort(403, 'Acesso negado! Você não pode acessar este usuário.');
        }
    }

    /**
 * Promove um usuário de cliente para admin.
 * Apenas admins podem executar.
 */
public function promoteToAdmin(User $user)
{
    $this->authorizeAdmin(); // verifica se quem está logado é admin

    $user->role = 'admin';
    $user->save();

    return redirect()->route('users.index')->with('success', $user->name . ' agora é admin!');
}

}

