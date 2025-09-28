@extends('layouts.admin')

@section('title', 'Detalhes do Carrinho')

@section('header')
    Carrinho #{{ $cart->id }}
@endsection

@section('content')
<div class="container">
    <h2>Carrinho #{{ $cart->id }}</h2>
    <p><strong>Cliente:</strong> {{ $cart->client->full_name ?? '-' }}</p>
    <p><strong>Session ID:</strong> {{ $cart->session_id }}</p>

    <h3>Itens do Carrinho</h3>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID Produto</th>
                <th>Título</th>
                <th>Quantidade</th>
                <th>Preço</th>
                @if(Auth::user()->role === 'admin')
                    <th>Ações</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($cart->items as $item)
                <tr id="item-{{ $item->id }}">
                    <td>{{ $item->id_products }}</td>
                    <td>
                        <input type="text" value="{{ $item->title }}" onchange="updateItem({{ $item->id }}, 'title', this.value)">
                    </td>
                    <td>
                        <input type="number" value="{{ $item->quantity }}" min="1" onchange="updateItem({{ $item->id }}, 'quantity', this.value)">
                    </td>
                    <td>
                        <input type="number" value="{{ $item->price }}" step="0.01" min="0" onchange="updateItem({{ $item->id }}, 'price', this.value)">
                    </td>
                    @if(Auth::user()->role === 'admin')
                        <td>
                            <button onclick="deleteItem({{ $item->id }})">Excluir</button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Adicionar Novo Item</h3>
    <form id="add-item-form">
        <label>Produto ID: <input type="number" name="id_products" required></label>
        <label>Título: <input type="text" name="title" required></label>
        <label>Quantidade: <input type="number" name="quantity" value="1" min="1" required></label>
        <label>Preço: <input type="number" name="price" value="0" min="0" step="0.01" required></label>
        <button type="submit">Adicionar Item</button>
    </form>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function updateItem(itemId, field, value) {
        fetch(`/cart-items/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ [field]: value })
        }).then(res => res.json())
          .then(data => { if(!data.success) alert('Erro ao atualizar item'); });
    }

    function deleteItem(itemId) {
        if(!confirm('Deseja realmente excluir este item?')) return;
        fetch(`/cart-items/${itemId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        }).then(res => res.json())
          .then(data => {
            if(data.success) document.getElementById(`item-${itemId}`).remove();
            else alert('Erro ao excluir item');
        });
    }

    document.getElementById('add-item-form').addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        const payload = Object.fromEntries(formData.entries());

        fetch(`/carts/{{ $cart->id }}/add-item`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(payload)
        }).then(res => res.json())
          .then(data => {
            if(data.success) location.reload();
            else alert('Erro ao adicionar item');
        });
    });
</script>
@endsection
