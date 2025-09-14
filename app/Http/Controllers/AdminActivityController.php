<?php

namespace App\Http\Controllers;

/** 
 * @method \Illuminate\Routing\Middleware middleware(string $name, array $options = [])
 */
use App\Models\AdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdminActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // protege todas as rotas
    }

    /**
     * Listar todas as atividades
     * Apenas admin pode acessar
     */
    public function index()
    {
        $this->authorizeAdmin();

        $activities = AdminActivity::with('admin')->orderBy('created_at', 'desc')->get();
        return view('admin_activities.index', compact('activities'));
    }

    /**
     * Criar atividade manualmente (opcional)
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'activity' => 'required|string|max:200',
        ]);

        AdminActivity::create([
            'id_admins' => Auth::id(),
            'activity' => $request->activity,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Atividade registrada com sucesso!');
    }

    /**
     * Função para verificar se o usuário é admin
     */
    private function authorizeAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acesso negado! Apenas admins podem executar esta ação.');
        }
    }
}