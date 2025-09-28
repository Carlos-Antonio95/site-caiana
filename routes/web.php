<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController, ClientController, AddressesController, CategoriesController,
    ProductsController, ProductsVariantsController, PromotionsController,
    ProductsImagesController, PromotionProductController, ProductReviewsController,
    CouponsController, CartItemController, CartsController, OrdersController,
    OrderItemsController, PaymentsController, AdminActivityController
};
use App\Http\Controllers\Auth\{
    NewPasswordController, ForgotPasswordController
};

// ===================== Rotas de visualização =====================
Route::view('/', 'index')->name('index');
Route::view('/produtos', 'produtos')->name('produtos');
Route::view('/promocoes', 'promocoes')->name('promocoes');
Route::view('/contato', 'contato')->name('contato');

Route::get('/login', function () {
    return view('login'); // mostra o formulário de login
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        if (auth()->user()->role === 'admin') {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('index');
        }
    }

    return back()->withErrors([
        'email' => 'As credenciais não conferem.',
    ]);
});

Route::view('/register', 'register')->name('register');
Route::get('/cart', [CartsController::class, 'showCart'])->name('cart.show');

// ===================== Autenticação =====================
Route::middleware('guest')->group(function () {
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
});

// ===================== Carrinho =====================
Route::middleware('auth')->group(function () {
    Route::post('/cart/checkout', [CartsController::class, 'checkout'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});

// ===================== Promoções =====================
Route::resource('promotions', PromotionsController::class);
Route::resource('promotion_products', PromotionProductController::class);

Route::post('/promotions/create-promotion', [PromotionsController::class, 'store'])
    ->name('promotions.index.create');
Route::post('/promotions/add-product', [PromotionProductController::class, 'store'])
    ->name('promotions.index.addProduct');

// ===================== Pedidos =====================
Route::post('/test/orders/{order}/status', [OrdersController::class, 'changeStatus']);
Route::view('/test-status', 'test-status');
Route::post('/test/orders/{order}/status', [OrdersController::class, 'changeStatusTest']);

// ===================== API pública =====================
Route::get('/api/products', [ProductsController::class, 'getAll'])->name('api.products');
Route::get('/products', [ProductsController::class, 'apiIndex']);

// ===================== Rotas CRUD protegidas =====================
Route::middleware('auth')->group(function () {
    Route::resources([
        'users' => UserController::class,
        'clients' => ClientController::class,
        'addresses' => AddressesController::class,
        'categories' => CategoriesController::class,
        'products' => ProductsController::class,
        'product_variants' => ProductsVariantsController::class,
        'promotions' => PromotionsController::class,
        'product_images' => ProductsImagesController::class,
        'promotion_products' => PromotionProductController::class,
        'product_reviews' => ProductReviewsController::class,
        'coupons' => CouponsController::class,
        'cart_items' => CartItemController::class,
        'carts' => CartsController::class,
        'orders' => OrdersController::class,
        'order_items' => OrderItemsController::class,
        'payments' => PaymentsController::class,
        'admin_activities' => AdminActivityController::class,
    ]);

    Route::put('/users/{user}/promote', [UserController::class, 'promoteToAdmin'])->name('users.promote');
    Route::get('/product_variants/{product}/create', [ProductsVariantsController::class, 'create'])->name('product_variants.create');
    Route::get('/product_reviews/create/{id}', [ProductReviewsController::class, 'create'])->name('product_reviews.create');
    Route::post('/addresses', [AddressesController::class, 'store'])->name('addresses.store');
});

// ===================== Dashboard =====================
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    });

// ===================== Rotas Admin =====================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/painel', function () { return view('admin.painel'); })->name('painel');

    Route::resource('products', ProductsController::class);
    Route::resource('promotions', PromotionsController::class);
    Route::resource('promotion-products', PromotionProductController::class);
    Route::resource('orders', OrdersController::class);
    Route::resource('users', UserController::class);
    Route::resource('admin-activities', AdminActivityController::class);
    Route::resource('coupons', CouponsController::class);
    Route::resource('clients', ClientController::class);
});
