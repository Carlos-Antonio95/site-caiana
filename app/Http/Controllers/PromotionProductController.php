<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\PromotionProduct;
use App\Models\Promotions;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionProductController extends Controller
{
    /**
     * Construtor do controller.
     * Aplica middleware 'auth' para proteger todas as rotas.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a lista de todas as promoções aplicadas aos produtos.
     * Inclui relações com 'promotion' e 'product' para mostrar detalhes.
     */
    public function index()
    {
        $items = PromotionProduct::with(['promotion', 'product'])->get();
        return view('promotion_products.index', compact('items'));
    }

    /**
     * Exibe o formulário para criar uma nova promoção de produto.
     * Apenas admins podem acessar.
     */
    public function create()
    {
        $this->authorizeAdmin();

        // Buscar todas as promoções e produtos para popular selects no formulário
        $promotions = Promotions::all();
        $products = Products::all();

        return view('promotion_products.create', compact('promotions', 'products'));
    }

    /**
     * Salva a nova promoção do produto no banco de dados.
     * Valida os dados enviados pelo formulário.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        // Validações
        $request->validate([
            'id_promotions' => 'required|exists:promotions,id', // Deve existir na tabela promotions
            'id_products' => 'required|exists:products,id',     // Deve existir na tabela products
            'percentage_discount' => 'nullable|numeric|min:0|max:100', // Percentual entre 0 e 100
            'promotional_price' => 'nullable|numeric|min:0',           // Preço promocional >= 0
        ]);

        // Cria o registro no banco
        PromotionProduct::create($request->all());

        return redirect()->route('promotion_products.index')
            ->with('success', 'Promoção aplicada ao produto com sucesso!');
    }

    /**
     * Exibe os detalhes de uma promoção específica de produto.
     */
    public function show(PromotionProduct $promotionProduct)
    {
        return view('promotion_products.show', compact('promotionProduct'));
    }

    /**
     * Exibe o formulário de edição de uma promoção de produto.
     * Apenas admins podem acessar.
     */
    public function edit(PromotionProduct $promotionProduct)
    {
        $this->authorizeAdmin();

        // Buscar todas as promoções e produtos para selects no formulário
        $promotions = Promotions::all();
        $products = Products::all();

        return view('promotion_products.edit', compact('promotionProduct', 'promotions', 'products'));
    }

    /**
     * Atualiza os dados de uma promoção de produto existente.
     * Valida os dados antes de atualizar.
     */
    public function update(Request $request, PromotionProduct $promotionProduct)
    {
        $this->authorizeAdmin();

        // Validações
        $request->validate([
            'id_promotions' => 'required|exists:promotions,id',
            'id_products' => 'required|exists:products,id',
            'percentage_discount' => 'nullable|numeric|min:0|max:100',
            'promotional_price' => 'nullable|numeric|min:0',
        ]);

        // Atualiza o registro
        $promotionProduct->update($request->all());

        return redirect()->route('promotion_products.index')
            ->with('success', 'Promoção do produto atualizada com sucesso!');
    }

    /**
     * Deleta uma promoção de produto.
     * Apenas admins podem executar.
     */
    public function destroy(PromotionProduct $promotionProduct)
    {
        $this->authorizeAdmin();

        $promotionProduct->delete();

        return redirect()->route('promotion_products.index')
            ->with('success', 'Promoção do produto removida com sucesso!');
    }

    /**
     * Função auxiliar para verificar se o usuário logado é admin.
     * Caso não seja, aborta com erro 403.
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}