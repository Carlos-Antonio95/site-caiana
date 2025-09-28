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
    // Middleware para proteger rotas CRUD
    public function __construct()
    {
       // $this->middleware('auth')->except(['getAll']); // só a função getAll será pública
    }

    // =================== JSON para front-end ===================
    /**
     * Retornar todos os produtos em JSON para o front
     */
    public function getAll()
    {
        // Pega produtos com a categoria e imagens
        $products = Products::with(['category', 'images'])->get();

        // Retorna JSON
        return response()->json($products);
    }

    public function apiIndex()
{
    // Pega todos os produtos com suas categorias e imagens
    $products = Products::with(['category', 'images'])->get();

    return response()->json($products);
}
    // =================== CRUD padrão ===================
    public function index()
    {
        $products = Products::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $categories = Categories::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_categories' => 'required|exists:categories,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:ativo,inativo',
        ]);

        Products::create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produto criado com sucesso!');
    }

    public function show(Products $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Products $product)
    {
        $this->authorizeAdmin();

        $categories = Categories::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Products $product)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_categories' => 'required|exists:categories,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|in:ativo,inativo',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function updateStatus(Request $request, Products $product)
    {
        $this->authorizeAdmin();

        $request->validate([
            'status' => 'required|in:ativo,inativo',
        ]);

        $product->update($request->only('status'));

        return redirect()->route('admin.products.index')->with('success', 'Status do produto atualizado com sucesso!');
    }

    public function updateStock(Request $request, Products $product)
    {
        $this->authorizeAdmin();

        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update($request->only('stock_quantity'));

        return redirect()->route('admin.products.index')->with('success', 'Quantidade em estoque atualizada com sucesso!');
    }
    
    public function destroy(Products $product)
    {
        $this->authorizeAdmin();

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produto deletado com sucesso!');
    }

    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}
