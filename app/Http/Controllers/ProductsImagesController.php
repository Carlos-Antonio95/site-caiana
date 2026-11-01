<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */ 
use App\Models\Products_Images;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
class ProductsImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todas as imagens
     */
    public function index()
    {
        $images = Products_Images::with('product')->get();
        return view('admin.product_images.index', compact('images'));
    }

    /**
     * Formulário para adicionar nova imagem
     */
    public function create()
    {
        $this->authorizeAdmin();

        $products = Products::all();
        return view('product_images.create', compact('products'));
    }

    /**
     * Salvar imagem no banco
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_products' => 'required|exists:products,id',
            'image_path' => ['required', 'string', 'max:255', 'regex:' . Products_Images::PATH_REGEX],
            'is_primary' => 'nullable|boolean',
        ]);

        Products_Images::create($request->only(['id_products', 'image_path', 'is_primary']));

        return redirect()->route('admin.product_images.index')->with('success', 'Imagem adicionada com sucesso!');
    }

    /**
     * Mostrar detalhes de uma imagem
     */
    public function show(Products_Images $productImage)
    {
        return view('product_images.show', compact('productImage'));
    }

    /**
     * Formulário para edição da imagem
     */
    public function edit(Products_Images $productImage)
    {
        $this->authorizeAdmin();

        $products = Products::all();
        return view('product_images.edit', compact('productImage', 'products'));
    }

    /**
     * Atualizar imagem
     */
    public function update(Request $request, Products_Images $productImage)
    {
        $this->authorizeAdmin();

        $request->validate([
            'id_products' => 'required|exists:products,id',
            'image_path' => ['required', 'string', 'max:255', 'regex:' . Products_Images::PATH_REGEX],
            'is_primary' => 'nullable|boolean',
        ]);

        $productImage->update($request->only(['id_products', 'image_path', 'is_primary']));

        return redirect()->route('admin.product_images.index')->with('success', 'Imagem atualizada com sucesso!');
    }

    /**
     * Deletar imagem
     */
    public function destroy(Products_Images $productImage)
{
    $this->authorizeAdmin();

    // Deletar arquivo físico (só dentro de public/assets)
    Products_Images::deleteFile($productImage->image_path);

    // Deletar registro do banco
    $productImage->delete();

    return redirect()->route('admin.product_images.index')->with('success', 'Imagem deletada com sucesso!');
}


    /**
     * Verifica se usuário logado é admin
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}
