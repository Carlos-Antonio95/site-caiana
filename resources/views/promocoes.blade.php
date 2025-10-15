@extends('layouts.app')

@section('title', 'CAIANA — Promoções')
@section('nav-index', 'is-active')

@section('content')
<main id="promocoes">
    <section class="container" style="padding-top:2rem;">
        <h2>Itens em Promoção</h2>

        {{-- O JS (renderPromotions) vai preencher essa div dinamicamente --}}
        <div class="promotions"></div>
    </section>

    <style>
        .promotions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: flex-start;
        }

        .promotions .card {
            flex: 1 1 250px;
            max-width: 250px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            overflow: hidden;
        }

        .promotions .card img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .promotions .card h4 {
            font-size: 1rem;
            margin: 0.5rem 0;
        }

        .promotions .card .price {
            margin-bottom: 0.5rem;
        }

        .promotions .card button {
            margin-top: auto;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .promotions .card {
                flex: 1 1 calc(33.33% - 1rem);
                max-width: none;
            }
        }

        @media (max-width: 768px) {
            .promotions .card {
                flex: 1 1 calc(50% - 1rem);
            }
        }

        @media (max-width: 480px) {
            .promotions {
                justify-content: center;
            }
            .promotions .card {
                flex: 1 1 100%;
                max-width: 100%;
            }
        }
    </style>
</main>
@endsection