<?php

namespace App\Http\Controllers;

use App\Models\Coupons;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponsController extends Controller
{
    public function __construct()
    {
        // Protege todas as rotas com autenticação
        $this->middleware('auth');
    }

    /**
     * Exibe a lista de cupons (somente admin)
     */
    public function index()
    {
        $this->authorizeAdmin();
        $coupons = Coupons::all();
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Exibe formulário de criação de cupom
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.coupons.create');
    }

    /**
     * Cria um novo cupom
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'code'            => 'required|string|max:50|unique:coupons,code',
            'discount_type'   => 'required|in:valor,percentual',
            'discount_value'  => 'required|numeric|min:0',
            'min_discount'    => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'max_use'         => 'required|integer|min:1',
            'active'          => 'required|boolean',
        ]);

        Coupons::create($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    /**
     * Exibe detalhes de um cupom
     */
    public function show(Coupons $coupon)
    {
        $this->authorizeAdmin();
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Exibe formulário de edição de cupom
     */
    public function edit(Coupons $coupon)
    {
        $this->authorizeAdmin();
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Atualiza um cupom
     */
    public function update(Request $request, Coupons $coupon)
    {
        $this->authorizeAdmin();

        $request->validate([
            'code'            => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type'   => 'required|in:valor,percentual',
            'discount_value'  => 'required|numeric|min:0',
            'min_discount'    => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'max_use'         => 'required|integer|min:1',
            'active'          => 'required|boolean',
        ]);

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Cupom atualizado com sucesso!');
    }

    /**
     * Deleta um cupom
     */
    public function destroy(Coupons $coupon)
    {
        $this->authorizeAdmin();
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Cupom deletado com sucesso!');
    }

    /**
     * Aplica cupom de desconto no carrinho
     */
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string|max:50'] + Products::CART_RULES);

        $coupon = Coupons::findByCode($request->code);

        if (!$coupon) {
            return response()->json(['error' => 'Cupom inválido ou inexistente.'], 404);
        }

        // Subtotal calculado no servidor a partir do carrinho (não confia no total do cliente)
        $subtotal = Products::subtotalFor($request->input('items'));

        if ($reason = $coupon->rejectionReason($subtotal)) {
            return response()->json(['error' => $reason], 400);
        }

        $discountValue = $coupon->discountFor($subtotal);

        // Só guarda o código: o desconto é recalculado e o uso é contado no checkout
        session(['applied_coupon' => $coupon->code]);

        return response()->json([
            'success'   => true,
            'discount'  => $discountValue, // envia número puro, sem formatar
            'new_total' => round($subtotal - $discountValue, 2),
        ]);
    }

    /**
     * Verifica se o usuário é admin
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}
