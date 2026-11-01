<?php
    use App\Models\Addresses;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\{
        UsersController, ClientController, AddressesController, CategoriesController,
        ProductsController, ProductsVariantsController, PromotionsController,
        ProductsImagesController, PromotionProductController, ProductReviewsController,
        CouponsController, CartsController, OrdersController,
        OrderItemsController, PaymentsController, AdminActivityController, CartItemController,
        ContactController
    };

    use App\Http\Controllers\Auth\{
        NewPasswordController, ForgotPasswordController
    };
    use App\Models\Client;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
  
    use App\Http\Controllers\HomeController;
    // ===================== Rotas Públicas =====================
    Route::get('/', [HomeController::class, 'index'])->name('index');
    //Route::view('/', 'index')->name('index');
   // Route::view('/produtos', 'produtos')->name('produtos');
    Route::get('/produtos', [ProductsController::class, 'publicIndex'])->name('produtos');
    Route::view('/promocoes', 'promocoes')->name('promocoes');
    Route::view('/contato', 'contato')->name('contato');
    Route::get('/login', fn() => view('login'))->name('login');
    Route::view('/register', 'register')->name('register');
    Route::get('/cart', [CartsController::class, 'showCart'])->name('cart.show');
    Route::get('/promocoes', [PromotionsController::class, 'siteIndex'])->name('promocoes');
    Route::get('/sucesso', [OrdersController::class, 'sucesso'])->name('sucesso');
    Route::view('/politica_privacidade', 'politica_privacidade')->name('politica');
    Route::view('/termos', 'termos')->name('termos');






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

    // Cria o cliente automaticamente e guarda na variável $client
    $client = Client::create([
        'full_name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'date_birth' => $data['date_birth'],
        'id_users' => $user->id, 
    ]);
    
    // Cria o endereço placeholder automaticamente usando $client->id
    
  //  Addresses::create([
    //    'id_clients' => $client->id,
      // 'road' => 'Endereço não informado',
        //'number' => '-',
//        'complement' => null,
  //      'referenc' => null,
    //    'neighborhood' => 'Não informado',
      //  'city' => 'Não informado',
     //   'state' => 'NA',
       // 'cep' => '00000-000',
    //    'country' => 'Brasil',
    //]);

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

    // ===================== Rotas do Cliente (Auth) =====================
    Route::middleware('auth')->group(function () {
        Route::get('/meus_pedidos', [OrdersController::class, 'meusPedidos'])->name('meus.pedidos');
        Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
        Route::post('/addresses', [AddressesController::class, 'store'])->name('addresses.store');

        // Cupom e checkout do carrinho
        Route::post('/apply-coupon', [CouponsController::class, 'applyCoupon'])->name('coupons.apply');
        Route::post('/cart/checkout', [CartsController::class, 'checkout']);
    });

    // ===================== Rotas Admin =====================
    Route::prefix('admin')
        ->middleware(['auth', 'admin'])
        ->name('admin.')
        ->group(function () {
            // Redireciona admin para dashboard
            Route::get('/', fn() => redirect()->route('dashboard'))->name('home');

            // CRUD Admin
            Route::resources([
                'products' => ProductsController::class,
                'product_variants' => ProductsVariantsController::class,
                'product_images' => ProductsImagesController::class,
                'product_reviews' => ProductReviewsController::class,
                'promotions' => PromotionsController::class,
                'promotion_products' => PromotionProductController::class,
                'orders' => OrdersController::class,
                'order_items' => OrderItemsController::class,
                'payments' => PaymentsController::class,
                'users' => UsersController::class,
                'admin-activities' => AdminActivityController::class,
                'coupons' => CouponsController::class,
                'clients' => ClientController::class,
                'addresses' => AddressesController::class,
                'carts' => CartsController::class,
                'cart_items' => CartItemController::class,
                'categories' => CategoriesController::class,
                'contacts' => ContactController::class,
            ]);
            Route::get('/product_variants/{product}/create', [ProductsVariantsController::class, 'create'])->name('product_variants.create');
            Route::post('/orders/{order}/status', [OrdersController::class, 'changeStatusTest'])->name('orders.status');
            Route::put('/users/{user}/promote', [UsersController::class, 'promoteToAdmin'])->name('users.promote');
            Route::put('/users/{user}/demote', [UsersController::class, 'demoteToClient'])->name('users.demote');
        });

    // ===================== Dashboard (Admin) =====================
    Route::middleware(['auth', 'admin'])
        ->get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // ===================== Rotas API Pública =====================
    Route::get('/api/products', [ProductsController::class, 'getAll'])->name('api.products');
    Route::get('/products', [ProductsController::class, 'apiIndex']);

    Route::post('/contato', [ContactController::class, 'store'])->name('contato.store');
