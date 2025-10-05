<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensagem' => 'required|string',
        ]);

        Contact::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'mensagem' => $request->mensagem,
            'session_id' => Session::getId(),
            'client_id' => Auth::check() ? Auth::id() : null,
        ]);

        return response()->json(['success' => true]);
    }

    // Listar todos os contatos
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('admin.contacts.index', compact('contacts'));
    }

    // Visualizar mensagem
    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    // Editar (marcar respondido)
    public function edit(Contact $contact)
    {
        return view('admin.contacts.edit', compact('contact'));
    }

    // Atualizar (marca como respondido)
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'respondido' => 'required|boolean',
        ]);

        $contact->update([
            'respondido' => $request->respondido,
        ]);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Status atualizado com sucesso!');
    }

    // Excluir mensagem
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Mensagem excluída com sucesso!');
    }
}
