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
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\AdminActivityController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// =================== Rotas de visualização ===================
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

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// =================== Rota pública API ===================
Route::get('/api/products', [ProductsController::class, 'getAll'])
    ->name('api.products'); // retorna todos os produtos em JSON
Route::get('/products', [ProductsController::class, 'apiIndex']);
// =================== Rotas CRUD ===================
Route::resource('users', UserController::class)->middleware('auth');
Route::resource('clients', ClientController::class)->middleware('auth');
Route::resource('adressesses', AddressesController::class)->middleware('auth');
Route::resource('categories', CategoriesController::class)->middleware('auth');
Route::resource('products', ProductsController::class)->middleware('auth');
Route::resource('product_variants', ProductsVariantsController::class)->middleware('auth');
Route::resource('promotions', PromotionsController::class)->middleware('auth');
Route::resource('product_images', ProductsImagesController::class)->middleware('auth');
Route::resource('promotion_products', PromotionProductController::class)->middleware('auth');
Route::resource('product_reviews', ProductReviewsController::class)->middleware('auth');
Route::resource('coupons', CouponsController::class)->middleware('auth');
Route::resource('cart_items', CartItemController::class)->middleware('auth');
Route::resource('carts', CartsController::class)->middleware('auth');
Route::resource('orders', OrdersController::class)->middleware('auth');
Route::resource('order_items', OrderItemsController::class)->middleware('auth');
Route::resource('payments', PaymentsController::class)->middleware('auth');
Route::resource('admin_activities', AdminActivityController::class)->middleware('auth');

// =================== Rotas especiais ===================
// Promover usuário a admin
Route::put('/users/{user}/promote', [UserController::class, 'promoteToAdmin'])
    ->name('users.promote')
    ->middleware('auth');

// Criar variantes de um produto específico
Route::get('/product_variants/{product}/create', [ProductsVariantsController::class, 'create'])
    ->name('product_variants.create')
    ->middleware('auth');

// Criar avaliação para um produto específico
Route::get('/product_reviews/create/{id}', [ProductReviewsController::class, 'create'])
    ->name('product_reviews.create')
    ->middleware('auth');

// Dashboard (protegido)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});