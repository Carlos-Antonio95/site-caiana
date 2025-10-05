<?php

namespace App\Http\Controllers;

use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
     * Valida um cupom (AJAX)
     */
    public function validateCoupon(Request $request)
    {
        $code = $request->input('code');

        $coupon = Coupons::where('code', $code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Cupom inválido ou expirado.'], 400);
        }

        return response()->json([
            'valid' => true,
            'code'  => $coupon->code,
            'type'  => $coupon->type,
            'value' => $coupon->discount_value,
        ]);
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

        return redirect()->route('coupons.index')->with('success', 'Cupom criado com sucesso!');
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
        return view('coupons.edit', compact('coupon'));
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
        $request->validate([
            'code'  => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        $coupon = Coupons::where('code', $request->code)
            ->where('active', true)
            ->first();

        if (!$coupon) {
            return response()->json(['error' => 'Cupom inválido ou inexistente.'], 404);
        }

        if (now()->gt($coupon->expiration_date)) {
            return response()->json(['error' => 'Cupom expirado.'], 400);
        }

        if ($coupon->max_use <= 0) {
            return response()->json(['error' => 'Este cupom atingiu o limite de usos.'], 400);
        }

        if ($request->total < $coupon->min_discount) {
            return response()->json([
                'error' => 'O valor mínimo para usar este cupom é R$ ' .
                    number_format($coupon->min_discount, 2, ',', '.')
            ], 400);
        }

        $discountValue = $coupon->discount_type === 'percentual'
            ? ($request->total * ($coupon->discount_value / 100))
            : $coupon->discount_value;

        $discountValue = min($discountValue, $request->total);

        // Decrementa o uso e salva na sessão
        $coupon->decrement('max_use');
        session(['discount' => $discountValue]);

        // Desativa se necessário
        $this->deactivateCoupon(new Request(['code' => $coupon->code]));

        return response()->json([
    'success'   => true,
    'discount'  => $discountValue, // envia número puro, sem formatar
    'new_total' => $request->total - $discountValue,
]);

    }

    /**
     * Desativa cupom manualmente
     */
    public function deactivateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupons::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['error' => 'Cupom inválido ou inexistente.'], 404);
        }

        if ($coupon->max_use <= 0 || now()->gt(Carbon::parse($coupon->expiration_date))) {
            $coupon->active = false;
            $coupon->save();
        }

        return response()->json(['success' => 'Cupom desativado com sucesso.']);
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
