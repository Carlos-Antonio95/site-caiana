<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Listar usuários
    public function index()
    {
        $this->authorizeAdmin();

        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Formulário criar usuário
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.users.create');
    }

    // Salvar usuário
   public function store(Request $request)
{
    $this->authorizeAdmin();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:cliente,admin',
    ]);

    try {
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->role = $request->role; // role não é mass assignable
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', '✅ Usuário criado com sucesso!');
    } catch (\Exception $e) {
        return redirect()
            ->route('admin.users.index')
            ->with('error', '❌ Erro ao criar usuário: ' . $e->getMessage());
    }
}


    // Mostrar usuário
    public function show(User $user)
    {
        $this->authorizeUser($user);
        return view('admin.users.show', compact('user'));
    }

    // Formulário editar usuário
    public function edit(User $user)
    {
        $this->authorizeUser($user);
        return view('admin.users.edit', compact('user'));
    }

    // Atualizar usuário
   public function update(Request $request, User $user)
{
    $this->authorizeUser($user);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
        'role' => 'sometimes|in:cliente,admin',
    ]);

    // Só admin altera role, e nunca rebaixa a si mesmo
    if ($request->filled('role') && $request->role !== $user->role) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem alterar o perfil de acesso.');
        }
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Você não pode rebaixar a si mesmo.');
        }
    }

    try {
        $data = $request->only(['name', 'email']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->fill($data);
        if ($request->filled('role') && Auth::user()->role === 'admin') {
            $user->role = $request->role;
        }
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', '✅ Usuário atualizado com sucesso!');
    } catch (\Exception $e) {
        return redirect()
            ->route('admin.users.index')
            ->with('error', '❌ Erro ao atualizar usuário: ' . $e->getMessage());
    }

}


    // Deletar usuário
    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        if (Auth::id() === $user->id || $user->id === 1 || $user->id === 2) {
            return redirect()->route('admin.users.index')->with('error', 'Você não pode deletar a si mesmo.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso!');
    }

    // Promover usuário a admin
    public function promoteToAdmin(User $user)
    {
        $this->authorizeAdmin();
        $user->role = 'admin';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', "$user->name agora é admin!");
    }

    public function demoteToClient(User $user)
    {
        $this->authorizeAdmin();

        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Você não pode rebaixar a si mesmo.');
        }

        $user->role = 'cliente';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', "$user->name agora é cliente!");
    }

    // Verifica se o usuário logado é admin
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }

    // Verifica se o usuário logado pode acessar este usuário
    private function authorizeUser(User $user)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $user->id) {
            abort(403, 'Acesso negado! Você não pode acessar este usuário.');
        }
    }
}
