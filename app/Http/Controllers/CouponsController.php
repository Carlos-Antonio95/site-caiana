<?php

namespace App\Http\Controllers;
/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CouponsController extends Controller

   {
    public function __construct()
    {
        // Aplica middleware auth para proteger todas as rotas
        $this->middleware('auth');
    }

    /**
     * Exibe a lista de cupons
     */
    public function index()
    {
        $this->authorizeAdmin();
        $coupons = Coupons::all();
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Exibe o formulário de criação de cupom
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.coupons.create');
    }

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
            'code' => $coupon->code,
            'type' => $coupon->type, // 'percent' ou 'fixed'
            'value' => $coupon->discount_value,
        ]);
    }
    /**
     * Salva um novo cupom no banco
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:valor,percentual',
            'discount_value' => 'required|numeric|min:0',
            'min_discount' => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'max_use' => 'required|integer|min:1',
            'active' => 'required|boolean',
        ]);

        Coupons::create($request->all());

        return redirect()->route('coupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um cupom
     */
    public function show(Coupons $coupon)
    {
        $this->authorizeAdmin();
        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Exibe o formulário de edição de um cupom
     */
    public function edit(Coupons $coupon)
    {
        $this->authorizeAdmin();
        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Atualiza os dados de um cupom
     */
    public function update(Request $request, Coupons $coupon)
    {
        $this->authorizeAdmin();

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:valor,percentual',
            'discount_value' => 'required|numeric|min:0',
            'min_discount' => 'required|numeric|min:0',
            'expiration_date' => 'required|date',
            'max_use' => 'required|integer|min:1',
            'active' => 'required|boolean',
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
 * Aplica um cupom de desconto no carrinho ou pedido
 */
public function applyCoupon(Request $request)
{
    $request->validate([
        'code' => 'required|string',
        'total' => 'required|numeric|min:0', // total do carrinho enviado
    ]);

    $coupon = Coupons::where('code', $request->code)
        ->where('active', true)
        ->first();

    if (!$coupon) {
        return response()->json(['error' => 'Cupom inválido ou inexistente.'], 404);
    }

    // Verifica expiração
    if (now()->gt($coupon->expiration_date)) {
        return response()->json(['error' => 'Cupom expirado.'], 400);
    }

    // Verifica usos restantes
    if ($coupon->max_use <= 0) {
        return response()->json(['error' => 'Este cupom atingiu o limite de usos.'], 400);
    }

    // Verifica valor mínimo
    if ($request->total < $coupon->min_discount) {
        return response()->json(['error' => 'O valor mínimo para usar este cupom é R$ ' . number_format($coupon->min_discount, 2, ',', '.')], 400);
    }

    // Calcula o desconto
    $discountValue = $coupon->discount_type === 'percentual'
        ? ($request->total * ($coupon->discount_value / 100))
        : $coupon->discount_value;

    // Garante que o desconto não seja maior que o total
    $discountValue = min($discountValue, $request->total);

    // Decrementa o uso do cupom
    $coupon->decrement('max_use');

    return response()->json([
        'success' => true,
        'discount' => number_format($discountValue, 2, ',', '.'),
        'new_total' => number_format($request->total - $discountValue, 2, ',', '.'),
    ]);
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