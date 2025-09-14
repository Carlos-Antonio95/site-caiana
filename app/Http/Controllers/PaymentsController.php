<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PaymentsController extends Controller
{
   
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem realizar esta ação.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();
        $payments = Payments::with('order')->get();
        return response()->json($payments);
    }

    public function show($id)
    {
        $this->authorizeAdmin();
        $payment = Payments::with('order')->findOrFail($id);
        return response()->json($payment);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'id_orders' => 'required|exists:orders,id',
            'method' => 'required|in:cartao_credito,cartao_debito,dinheiro,pix',
            'amount' => 'required|numeric|min:0',
            'status' => 'in:pendente,aprovado,recusado'
        ]);

        $payment = Payments::create($validated);

        return response()->json($payment, 201);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $payment = Payments::findOrFail($id);

        $validated = $request->validate([
            'id_orders' => 'sometimes|exists:orders,id',
            'method' => 'sometimes|in:cartao_credito,cartao_debito,dinheiro,pix',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pendente,aprovado,recusado'
        ]);

        $payment->update($validated);

        return response()->json($payment);
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        $payment = Payments::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Pagamento excluído com sucesso.']);
    }
}