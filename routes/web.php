<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AddressesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductsVariantsController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\ProductsImagesController;
use App\Http\Controllers\PromotionProductController;
use App\Http\Controllers\ProductReviewsController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrderItemsController;
use app\Http\Controllers\PaymentsController;
use App\Http\Controllers\AdminActivityController;

//rotas de visualização
Route::get('/', function () {
    return view('index');
})->name('index');
Route::get('/produtos', function () {
    return view('produtos');
})->name('produtos');
Route::get('/promocoes', function () {
    return view('promocoes');
})->name('promocoes');
Route::get('/contato', function () {
    return view('contato');
})->name('contato');




// Rotas CRUD normais
Route::resource('users', UserController::class) ->middleware('auth'); // protege todas as rotas de users para usuários logados
Route::resource('clients', ClientController::class) ->middleware('auth'); // protege todas as rotas de clients para usuários logados
Route::resource('adressesses',AddressesController::class) ->middleware('auth'); // protege todas as rotas de addresses para usuários logados
Route::resource('categories', CategoriesController::class)->middleware('auth'); // protege todas as rotas de categories para usuários logados
Route::resource('products', ProductsController::class)->middleware('auth'); // protege todas as rotas de products para usuários logados
Route::resource('product_variants', ProductsVariantsController::class)->middleware('auth'); // protege todas as rotas de product_variants para usuários logados
Route::resource('promotions', PromotionsController::class)->middleware('auth'); // protege todas as rotas de promotions para usuários logados
Route::resource('product_images', ProductsImagesController::class)->middleware('auth'); // protege todas as rotas de product_images para usuários logados
Route::resource('promotion_products', PromotionProductController::class)->middleware('auth'); // protege todas as rotas de promotion_products para usuários logados
Route::resource('product_reviews', ProductReviewsController::class)->middleware('auth'); // protege todas as rotas de product_reviews para usuários logados
Route::resource('coupons', CouponsController::class)->middleware('auth'); // protege todas as rotas de coupons para usuários logados
Route::resource('cart_items', CartItemController::class)->middleware('auth'); // protege todas as rotas de cart_items para usuários logados
Route::resource('carts', CartsController::class)->middleware('auth'); // protege todas as rotas de carts para usuários logados
Route::resource('orders', OrdersController::class)->middleware('auth'); // protege todas as rotas de orders para usuários logados
Route::resource('order_items', OrderItemsController::class)->middleware('auth'); // protege todas as rotas de order_items para usuários logados
Route::resource('payments', PaymentsController::class)->middleware('auth'); // protege todas as rotas de payments para usuários logados
Route::resource('admin_activities', AdminActivityController::class)->middleware('auth');



// Rota para promover cliente a admin
Route::put('/users/{user}/promote', [UserController::class, 'promoteToAdmin'])
     ->name('users.promote')
     ->middleware('auth'); // protege para usuários logados

    Route::get('/product_variants/{product}/create', [ProductsVariantsController::class, 'create'])->name('product_variants.create')->middleware('auth'); // rota personalizada para criar variantes de um produto específico


    Route::get('/product_reviews/create/{id}', [ProductReviewsController::class, 'create'])->name('product_reviews.create')->middleware('auth'); // rota personalizada para criar avaliação para um produto específico
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

