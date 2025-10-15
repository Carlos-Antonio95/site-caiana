    @extends('layouts.admin')

    @section('title', 'Dashboard')

    @section('header')
        Dashboard
    @endsection

    @section('content')
        <div class="container">
            <!-- Card Produtos -->
            <div class="card">
                <h2>Produtos</h2>
                <p>Total: {{ \App\Models\Products::count() }}</p>
                <a href="{{ route('admin.products.index') }}">Gerenciar</a>
            </div>

            <!-- Card Promoções -->
            <div class="card">
                <h2>Promoções</h2>
                <p>Total: {{ \App\Models\Promotions::count() }}</p>
                <a href="{{ route('admin.promotions.index') }}">Gerenciar</a>
            </div>

            <!-- Card Pedidos -->
            <div class="card">
                <h2>Pedidos</h2>
                <p>Total: {{ \App\Models\Orders::count() }}</p>
                <a href="{{ route('admin.orders.index') }}">Gerenciar</a>
            </div>
        </div>
    @endsection
