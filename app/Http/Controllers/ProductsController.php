<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;

class ProductsController extends Controller
 {
    // Middleware para proteger rotas
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todos os produtos
     */
    public function index()
    {
        $products = Products::with('category')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Mostrar formulário para criar novo produto
     */
    public function create()
    {
        $this->authorizeAdmin();

        $categories = Categories::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Salvar produto no banco
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_categories' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:ativo,inativo',
        ]);

        Products::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Mostrar detalhes de um produto
     */
    public function show(Products $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Products $product)
    {
        $this->authorizeAdmin();

        $categories = Categories::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Atualizar produto no banco
     */
    public function update(Request $request, Products $product)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_categories' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:ativo,inativo',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Deletar produto
     */
    public function destroy(Products $product)
    {
        $this->authorizeAdmin();

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produto deletado com sucesso!');
    }

    /**
     * Função para verificar se o usuário logado é admin
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}