<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\Products;
use App\Models\Products_Variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsVariantsController extends Controller
{
   // Middleware para proteger rotas
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todas as variantes
     */
    public function index()
    {
        $variants = Products_Variants::with('product')->get();
        return view('product_variants.index', compact('variants'));
    }

    /**
     * Mostrar formulário para criar nova variante
     */
    public function create()
    {
        $this->authorizeAdmin();

        $products = Products::all();
        return view('product_variants.create', compact('products'));
    }

    /**
     * Salvar variante no banco
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_products' => 'required|exists:products,id',
            'size' => 'required|string|max:10',
            'color' => 'required|string|max:50',
            'additional_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        Products_Variants::create($request->all());

        return redirect()->route('product_variants.index')->with('success', 'Variante criada com sucesso!');
    }

    /**
     * Mostrar detalhes de uma variante
     */
    public function show(Products_Variants $productVariant)
    {
        return view('product_variants.show', compact('productVariant'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Products_Variants $productVariant)
    {
        $this->authorizeAdmin();

        $products = Products::all();
        return view('product_variants.edit', compact('productVariant', 'products'));
    }

    /**
     * Atualizar variante no banco
     */
    public function update(Request $request, Products_Variants $productVariant)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_products' => 'required|exists:products,id',
            'size' => 'required|string|max:10',
            'color' => 'required|string|max:50',
            'additional_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $productVariant->update($request->all());

        return redirect()->route('product_variants.index')->with('success', 'Variante atualizada com sucesso!');
    }

    /**
     * Deletar variante
     */
    public function destroy(Products_Variants $productVariant)
    {
        $this->authorizeAdmin();

        $productVariant->delete();

        return redirect()->route('product_variants.index')->with('success', 'Variante deletada com sucesso!');
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