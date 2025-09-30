<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\Promotions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionsController extends Controller
{
    // Middleware para proteger rotas
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Listar todas as promoções
     */
    public function index()
    {
        $promotions = Promotions::all();
        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Mostrar formulário para criar nova promoção
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.promotions.create');
    }

    /**
     * Salvar promoção no banco
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'banner' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);

        Promotions::create($request->all());

        return redirect()->route('admin.promotions.index')->with('success', 'Promoção criada com sucesso!');
    }

    /**
     * Mostrar detalhes de uma promoção
     */
    public function show(Promotions $promotion)
    {
        return view('admin.promotions.show', compact('promotion'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Promotions $promotion)
    {
        $this->authorizeAdmin();
        return view('admin.promotions.edit', compact('promotion'));
    }

    /**
     * Atualizar promoção no banco
     */
    public function update(Request $request, Promotions $promotion)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'banner' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);

        $promotion->update($request->all());

        return redirect()->route('admin.promotions.index')->with('success', 'Promoção atualizada com sucesso!');
    }

    /**
     * Deletar promoção
     */
    public function destroy(Promotions $promotion)
    {
        $this->authorizeAdmin();

        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promoção deletada com sucesso!');
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
