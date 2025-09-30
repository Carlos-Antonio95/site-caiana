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
    $products = Products::with(['category', 'images'])->get();

    // Adiciona final_price
    $products = $products->map(function($p) {
        return [
            'id' => $p->id,
            'title' => $p->title,
            'description' => $p->description,
            'price' => $p->price,
            'final_price' => $p->final_price, // aqui
            'stock_quantity' => $p->stock_quantity,
            'status' => $p->status,
            'category' => $p->category,
            'images' => $p->images
        ];
    });

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
        'id_categories'   => 'required|exists:categories,id',
        'title'           => 'required|string|max:150',
        'description'     => 'nullable|string',
        'price'           => 'required|numeric|min:0',
        'stock_quantity'  => 'required|integer|min:0',
        'status'          => 'required|in:ativo,inativo',
    ]);

    // cria só com os campos da tabela products
    $product = Products::create($request->only([
        'id_categories',
        'title',
        'description',
        'price',
        'stock_quantity',
        'status',
    ]));

    // se tiver imagem no request, salva em products_images
    if ($request->hasFile('image')) {
    $path = $request->file('image')->store('assets', 'public');

    \App\Models\Products_Images::create([
        'id_products' => $product->id,
        'image_path'  => 'storage/' . $path, // correto para acessar em <img src="{{ asset(...) }}">
        'is_primary'  => true,
    ]);
}


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
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // valida nova imagem
    ]);

    // Atualiza dados do produto
    $product->update($request->only([
        'id_categories',
        'title',
        'description',
        'price',
        'stock_quantity',
        'status',
    ]));

    // Se enviou nova imagem, substitui a principal
    if ($request->hasFile('image_path')) {
        $file = $request->file('image_path');
        $path = $file->store('products', 'public'); // storage/app/public/products

        $image = $product->images->first(); // pega a primeira imagem

        if ($image) {
            // opcional: deletar a imagem antiga do storage
            if (file_exists(public_path($image->image_path))) {
                @unlink(public_path($image->image_path));
            }
            $image->update(['image_path' => 'storage/' . $path]);
        } else {
            $product->images()->create([
                'image_path' => 'storage/' . $path,
                'is_primary' => true,
            ]);
        }
    }

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
