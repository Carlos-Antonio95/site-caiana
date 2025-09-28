<hr>
<h3>Itens do Pedido</h3>
@if(Auth::user()->role === 'admin')
    <a href="{{ route('order_items.create', ['order_id' => $order->id]) }}" class="btn btn-primary mb-2">+ Adicionar Item</a>
@endif
<table class="table">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Variante</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Total</th>
            @if(Auth::user()->role === 'admin')
                <th>Ações</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($order->items as $item)
            <tr>
                <td>{{ $item->title ?? $item->product_name }}</td>
                <td>{{ $item->variant->name ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                @if(Auth::user()->role === 'admin')
                    <td>
                        <a href="{{ route('order_items.edit', $item) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('order_items.destroy', $item) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Excluir item?')">Excluir</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ Auth::user()->role === 'admin' ? 6 : 5 }}">Nenhum item neste pedido.</td>
            </tr>
        @endforelse
    </tbody>
</table>
