<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UsersController, ClientController, AddressesController, CategoriesController,
    ProductsController, ProductsVariantsController, PromotionsController,
    ProductsImagesController, PromotionProductController, ProductReviewsController,
    CouponsController, CartItemController, CartsController, OrdersController,
    OrderItemsController, PaymentsController, AdminActivityController
};

use App\Http\Controllers\Auth\{
    NewPasswordController, ForgotPasswordController
};
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ===================== Rotas Públicas =====================
Route::view('/', 'index')->name('index');
Route::view('/produtos', 'produtos')->name('produtos');
Route::view('/promocoes', 'promocoes')->name('promocoes');
Route::view('/contato', 'contato')->name('contato');
Route::get('/login', fn() => view('login'))->name('login');
Route::view('/register', 'register')->name('register');
Route::get('/cart', [CartsController::class, 'showCart'])->name('cart.show');

// ===================== Login =====================
Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        return auth()->user()->role === 'admin'
            ? redirect()->route('dashboard')
            : redirect()->route('index');
    }

    return back()->withErrors([
        'email' => 'As credenciais não conferem.',
    ]);
});

// ===================== Registro =====================
Route::post('/register', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone' => 'required|string',
        'date_birth' => 'required|date',
        'password' => 'required|string|confirmed|min:6',
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'date_birth' => $data['date_birth'],
        'password' => Hash::make($data['password']),
    ]);

    // Cria o cliente automaticamente
    Client::create([
        'full_name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'date_birth' => $data['date_birth'],
        'id_users' => $user->id, 
    ]);

    Auth::login($user);

    return redirect()->route('index');
})->name('register.store');

// ===================== Rotas de Autenticação (Guest) =====================
Route::middleware('guest')->group(function () {
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
});

// ===================== Rotas Protegidas (Auth) =====================
Route::middleware('auth')->group(function () {

    // Checkout do carrinho
    Route::post('/cart/checkout', [CartsController::class, 'checkout'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    // CRUD geral protegido por auth
    Route::resources([
        'users' => UsersController::class,
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

    // Rotas extras CRUD
    Route::put('/users/{user}/promote', [UsersController::class, 'promoteToAdmin'])->name('users.promote');
    Route::get('/product_variants/{product}/create', [ProductsVariantsController::class, 'create'])->name('product_variants.create');
    Route::get('/product_reviews/create/{id}', [ProductReviewsController::class, 'create'])->name('product_reviews.create');
    Route::post('/addresses', [AddressesController::class, 'store'])->name('addresses.store');

});

// ===================== Dashboard =====================
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->get('/dashboard', fn() => view('dashboard'))->name('dashboard');

// ===================== Rotas Admin =====================
Route::prefix('admin')
    ->middleware(['auth'])
    ->name('admin.')
    ->group(function () {
        // Redireciona admin para dashboard
        Route::get('/', fn() => redirect()->route('dashboard'))->name('home');

        // CRUD Admin
        Route::resources([
            'products' => ProductsController::class,
            'promotions' => PromotionsController::class,
            'promotion-products' => PromotionProductController::class,
            'orders' => OrdersController::class,
            'users' => UsersController::class,
            'admin-activities' => AdminActivityController::class,
            'coupons' => CouponsController::class,
            'clients' => ClientController::class,
        ]);
                Route::put('/users/{user}/promote', [UsersController::class, 'promoteToAdmin'])->name('users.promote');
    });

// ===================== Rotas API Pública =====================
Route::get('/api/products', [ProductsController::class, 'getAll'])->name('api.products');
Route::get('/products', [ProductsController::class, 'apiIndex']);

// ===================== Rotas de Teste Pedidos =====================
Route::post('/test/orders/{order}/status', [OrdersController::class, 'changeStatus']);
Route::view('/test-status', 'test-status');
Route::post('/test/orders/{order}/status', [OrdersController::class, 'changeStatusTest']);
