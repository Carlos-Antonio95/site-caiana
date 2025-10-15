@extends('layouts.admin')

@section('title', 'Imagens dos Produtos')

@section('header')
    Gerenciar Imagens
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>Imagens Cadastradas</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pré-visualização</th>
                    <th>Nome / Caminho</th>
                    <th>Produto</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($images as $image)
                    <tr>
                        <td>
                            <img src="{{ asset($image->image_path) }}" alt="Imagem" width="80">
                        </td>
                        <td>{{ basename($image->image_path) }}</td>
                        <td>
                            @if($image->product)
                                {{ $image->product->title }}
                            @else
                                <span style="color:red;">Sem produto</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('product_images.destroy', $image) }}" method="POST" 
                                  onsubmit="return confirm('Deseja realmente excluir esta imagem?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Nenhuma imagem encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
