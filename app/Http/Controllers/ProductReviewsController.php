<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\ProductReviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ProductReviewsController extends Controller
{ public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar avaliações
     * Admin vê todas, cliente só vê as próprias
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $reviews = ProductReviews::with(['product', 'client'])->get();
        } else {
            $reviews = ProductReviews::with('product')
                ->where('id_clients', $user->id)
                ->get();
        }

        return view('product_reviews.index', compact('reviews'));
    }

    /**
     * Mostrar formulário para criar avaliação
     */
    public function create()
    {
        return view('product_reviews.create');
    }

    /**
     * Salvar avaliação
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_products' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ]);

        ProductReviews::create([
            'id_products' => $request->id_products,
            'id_clients' => Auth::id(),
            'rating' => $request->rating,
            'comments' => $request->comments,
            'status' => 'pendente', // avaliação inicial
        ]);

        return redirect()->route('product_reviews.index')->with('success', 'Avaliação enviada com sucesso!');
    }

    /**
     * Mostrar avaliação
     */
    public function show(ProductReviews $productReview)
    {
        $this->authorizeReview($productReview);
        return view('product_reviews.show', compact('productReview'));
    }

    /**
     * Editar avaliação
     */
    public function edit(ProductReviews $productReview)
    {
        $this->authorizeReview($productReview);
        return view('product_reviews.edit', compact('productReview'));
    }

    /**
     * Atualizar avaliação
     */
    public function update(Request $request, ProductReviews $productReview)
    {
        $this->authorizeReview($productReview);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'status' => 'nullable|in:pendente,aprovado,rejeitado', // só admin pode mudar
        ]);

        // Clientes não podem alterar status
        if (Auth::user()->role !== 'admin') {
            $request->merge(['status' => $productReview->status]);
        }

        $productReview->update($request->all());

        return redirect()->route('product_reviews.index')->with('success', 'Avaliação atualizada com sucesso!');
    }

    /**
     * Deletar avaliação
     * Admin pode deletar qualquer uma
     * Cliente pode deletar a própria
     */
    public function destroy(ProductReviews $productReview)
    {
        $this->authorizeReview($productReview);

        $productReview->delete();

        return redirect()->route('product_reviews.index')->with('success', 'Avaliação deletada com sucesso!');
    }

    /**
     * Verifica se o usuário logado pode acessar a avaliação
     */
    private function authorizeReview(ProductReviews $review)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $review->id_clients !== $user->id) {
            abort(403, 'Acesso negado! Você não pode acessar esta avaliação.');
        }
    }
}