<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use App\Models\Products_Images;


class ProductsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth')->except(['getAll']);
    }

    // =================== JSON para front-end ===================
    public function getAll()
    {
        $products = Products::with(['category', 'images'])->get();

        $products = $products->map(function($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'description' => $p->description,
                'price' => $p->price,
                'final_price' => $p->final_price,
                'stock_quantity' => $p->stock_quantity,
                'status' => $p->status,
                'category' => [
                    //'category_name' => $p->category?->title ?? ''
                     'category_name' => $p->category ? $p->category->category_name : ''
                ],
                'images' => $p->images->map(fn($img) => [
                    'image_path' => $img->image_path
                ])
            ];
        });

        return response()->json($products);
    }

    public function apiIndex()
    {
        $products = Products::with(['category', 'images'])->get();
        return response()->json($products);
    }

    // =================== CRUD ===================
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

public function publicIndex()
{
    $categories = Categories::all(); // pega todas as categorias
    return view('produtos', compact('categories'));
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
            'image'           => 'nullable|' . Products_Images::UPLOAD_RULES,
        ]);

        $product = Products::create($request->only([
            'id_categories',
            'title',
            'description',
            'price',
            'stock_quantity',
            'status',
        ]));

        if ($request->hasFile('image')) {
            $path = Products_Images::storeUpload($request->file('image'));

            Products_Images::create([
                'id_products' => $product->id,
                'image_path'  => $path,
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
            'image_path' => 'nullable|' . Products_Images::UPLOAD_RULES,
        ]);

        $product->update($request->only([
            'id_categories',
            'title',
            'description',
            'price',
            'stock_quantity',
            'status',
        ]));

        if ($request->hasFile('image_path')) {
            $path = Products_Images::storeUpload($request->file('image_path'));

            $image = $product->images->first();
            if ($image) {
                // Deleta arquivo antigo
                Products_Images::deleteFile($image->image_path);
                $image->update(['image_path' => $path]);
            } else {
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => true,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function updateStatus(Request $request, Products $product)
    {
        $this->authorizeAdmin();
        $request->validate(['status' => 'required|in:ativo,inativo']);
        $product->update($request->only('status'));
        return redirect()->route('admin.products.index')->with('success', 'Status do produto atualizado com sucesso!');
    }

    public function updateStock(Request $request, Products $product)
    {
        $this->authorizeAdmin();
        $request->validate(['stock_quantity' => 'required|integer|min:0']);
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
