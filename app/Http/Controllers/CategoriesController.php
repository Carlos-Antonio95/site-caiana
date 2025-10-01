<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\categories;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
class CategoriesController extends Controller
{  // Middleware para proteger rotas
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todas as categorias
     * Admin vê todos, cliente apenas visualiza (se aplicável)
     */
    public function index()
    {
        $categories = Categories::all(); // qualquer usuário logado vê
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Mostrar formulário para criar nova categoria
     * Apenas admin
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.categories.create');
    }

    /**
     * Salvar categoria no banco
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        Categories::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Mostrar detalhes de uma categoria
     */
    public function show(Categories $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Categories $category)
    {
        $this->authorizeAdmin();
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Atualizar categoria no banco
     */
    public function update(Request $request, Categories $category)
    {
        $this->authorizeAdmin();

        $request->validate([
            'category_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Deletar categoria
     */
    public function destroy(Categories $category)
    {
        $this->authorizeAdmin();

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoria deletada com sucesso!');
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