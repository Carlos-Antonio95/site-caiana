<?php

namespace Database\Seeders;

use App\Models\Addresses;
use App\Models\Categories;
use App\Models\Client;
use App\Models\Coupons;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Payments;
use App\Models\Products;
use App\Models\Products_Images;
use App\Models\PromotionProduct;
use App\Models\Promotions;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Dados de demonstração para o portfólio.
 *
 * Não é chamado pelo DatabaseSeeder:
 *   php artisan db:seed --class=DemoSeeder
 *
 * Pode rodar várias vezes: catálogo, cupons e promoção são atualizados
 * (datas recalculadas a partir de hoje); clientes e pedidos só são criados
 * na primeira execução.
 */
class DemoSeeder extends Seeder
{
    /** Catálogo real da loja (dump da hospedagem), todos ativos e com estoque. */
    private const CATEGORIES = ['Blusas', 'Vestidos', 'Saias', 'Conjuntos'];

    private const PRODUCTS = [
        ['Blusas', 'Blusa Melissa', 'Blusa de couro com recorte assimétrico e alça unilateral, cheia de estilo e autenticidade 🌟', 34.90, 6, 'assets/68eda69aa33ec.jpeg'],
        ['Blusas', 'Cropped Luisa', 'Cropped Vinho de babados com detalhe nas costas ✨', 39.90, 5, 'assets/68eda7d913c6e.jpeg'],
        ['Blusas', 'Cropped Lara', 'Cropped com strass disponível em três cores! Aquela peça que vai estilizar seu look de forma discreta e elegante', 39.99, 7, 'assets/68ed405eb3193.jpg'],
        ['Vestidos', 'Vestido Luna', 'Vestido marrom com detalhe no busto, recorte na cintura e strass pelo vestido, provando ser o look perfeito pra uma night com os amigos 🤩', 89.90, 4, 'assets/68edaac03b645.jpeg'],
        ['Conjuntos', 'Conjunto Maria', 'Conjunto florido de cropped e saia longa', 84.90, 3, 'assets/68edaba81fb60.jpeg'],
        ['Saias', 'Saia Midnight', 'Saia de couro com detalhe em dourado, peça essencial no guarda-roupa de uma mulher versátil', 39.90, 5, 'assets/68edad023fc62.jpeg'],
        ['Blusas', 'Cropped Marina', 'Cropped básico com detalhe de laço no busto, perfeita harmonia entre o simples e a feminilidade', 29.90, 8, 'assets/68edad9463032.jpeg'],
        ['Saias', 'Saia simple', 'Saia de couro bege com detalhe, ideal pra montar uma sequência de looks perfeita!', 39.90, 6, 'assets/68edade021da5.jpeg'],
        ['Conjuntos', 'Conjunto Camila', 'Conjunto monocromático', 90.00, 4, 'assets/68edae18cabde.jpeg'],
    ];

    /** Clientes fictícios: e-mails example.org, telefones com DDD 00. */
    private const CLIENTS = [
        ['Ana Beatriz Souza', 'ana.souza@example.org', '(00) 91234-0001', '1996-03-12', 'Rua das Acácias', '120', 'Boa Vista', 'Recife', 'PE'],
        ['Juliana Ferreira', 'juliana.ferreira@example.org', '(00) 91234-0002', '1992-07-25', 'Avenida Atlântica', '45', 'Centro', 'Olinda', 'PE'],
        ['Mariana Lima', 'mariana.lima@example.org', '(00) 91234-0003', '1999-11-03', 'Rua do Sol', '310', 'Casa Forte', 'Recife', 'PE'],
        ['Camila Rocha', 'camila.rocha@example.org', '(00) 91234-0004', '1988-01-30', 'Rua Projetada A', '18', 'Candeias', 'Jaboatão dos Guararapes', 'PE'],
        ['Larissa Alves', 'larissa.alves@example.org', '(00) 91234-0005', '2001-05-19', 'Travessa das Flores', '7', 'Graças', 'Recife', 'PE'],
        ['Fernanda Costa', 'fernanda.costa@example.org', '(00) 91234-0006', '1994-09-08', 'Rua da Aurora', '502', 'Santo Amaro', 'Recife', 'PE'],
        ['Patrícia Mendes', 'patricia.mendes@example.org', '(00) 91234-0007', '1985-12-14', 'Rua Exemplo', '99', 'Bairro Novo', 'Olinda', 'PE'],
        ['Beatriz Nogueira', 'beatriz.nogueira@example.org', '(00) 91234-0008', '1997-04-02', 'Avenida Central', '1500', 'Piedade', 'Jaboatão dos Guararapes', 'PE'],
    ];

    /** [cliente, dias atrás, status, [[produto, qtd], ...]] */
    private const ORDERS = [
        [0, 150, 'entregue', [['Vestido Luna', 1]]],
        [1, 138, 'entregue', [['Cropped Lara', 2], ['Saia Midnight', 1]]],
        [2, 121, 'entregue', [['Conjunto Camila', 1]]],
        [3, 110, 'cancelado', [['Blusa Melissa', 1]]],
        [4, 97, 'entregue', [['Cropped Marina', 1], ['Saia simple', 1]]],
        [5, 85, 'entregue', [['Conjunto Maria', 1]]],
        [6, 72, 'entregue', [['Cropped Luisa', 1], ['Cropped Marina', 1]]],
        [7, 60, 'entregue', [['Vestido Luna', 1], ['Cropped Lara', 1]]],
        [0, 46, 'entregue', [['Saia Midnight', 1]]],
        [2, 33, 'enviado', [['Cropped Lara', 1], ['Saia simple', 1]]],
        [1, 21, 'enviado', [['Conjunto Camila', 1]]],
        [5, 12, 'pago', [['Blusa Melissa', 1], ['Saia Midnight', 1]]],
        [3, 6, 'aprovado', [['Cropped Luisa', 2]]],
        [7, 3, 'pendente', [['Conjunto Maria', 1]]],
        [4, 1, 'pendente', [['Cropped Marina', 2]]],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $products = $this->seedCatalog();
            $this->seedCouponsAndPromotion($products);
            $clients = $this->seedClients();
            $this->seedOrders($clients, $products);
        });
    }

    /** @return array<string, Products> produtos indexados pelo título */
    private function seedCatalog(): array
    {
        $categories = [];
        foreach (self::CATEGORIES as $name) {
            $categories[$name] = Categories::firstOrCreate(['category_name' => $name]);
        }

        $products = [];
        foreach (self::PRODUCTS as [$category, $title, $description, $price, $stock, $image]) {
            $product = Products::updateOrCreate(
                ['title' => $title],
                [
                    'id_categories' => $categories[$category]->id,
                    'description' => $description,
                    'price' => $price,
                    'stock_quantity' => $stock,
                    'status' => 'ativo',
                ]
            );

            Products_Images::updateOrCreate(
                ['id_products' => $product->id, 'image_path' => $image],
                ['is_primary' => true]
            );

            $products[$title] = $product;
        }

        return $products;
    }

    private function seedCouponsAndPromotion(array $products): void
    {
        $today = Carbon::today();

        Coupons::updateOrCreate(['code' => 'BEMVINDO10'], [
            'discount_type' => 'percentual',
            'discount_value' => 10,
            'min_discount' => 50,
            'expiration_date' => $today->copy()->addYear(),
            'max_use' => 1000,
            'active' => true,
        ]);

        Coupons::updateOrCreate(['code' => 'CAIANA15'], [
            'discount_type' => 'valor',
            'discount_value' => 15,
            'min_discount' => 100,
            'expiration_date' => $today->copy()->addMonths(3),
            'max_use' => 1000,
            'active' => true,
        ]);

        $promotion = Promotions::updateOrCreate(['name' => 'Semana Brasilidade'], [
            'description' => 'Descontos especiais em peças selecionadas.',
            'start_date' => $today,
            'end_date' => $today->copy()->addDays(30),
            'active' => true,
        ]);

        foreach (['Vestido Luna' => 15, 'Cropped Lara' => 10, 'Conjunto Camila' => 20] as $title => $percentage) {
            PromotionProduct::updateOrCreate(
                ['id_promotions' => $promotion->id, 'id_products' => $products[$title]->id],
                ['percentage_discount' => $percentage, 'promotional_price' => null]
            );
        }
    }

    /** @return array<int, Client> */
    private function seedClients(): array
    {
        $clients = [];
        foreach (self::CLIENTS as [$name, $email, $phone, $birth, $road, $number, $neighborhood, $city, $state]) {
            $user = User::firstOrCreate(['email' => $email], [
                'name' => $name,
                'password' => Str::random(40), // conta só de exemplo, sem senha conhecida
                'role' => 'cliente',
            ]);

            $client = Client::firstOrCreate(['id_users' => $user->id], [
                'full_name' => $name,
                'phone' => $phone,
                'date_birth' => $birth,
            ]);

            Addresses::firstOrCreate(['id_clients' => $client->id], [
                'road' => $road,
                'number' => $number,
                'neighborhood' => $neighborhood,
                'city' => $city,
                'state' => $state,
                'cep' => '00000-000',
                'country' => 'Brasil',
            ]);

            $clients[] = $client;
        }

        return $clients;
    }

    private function seedOrders(array $clients, array $products): void
    {
        $clientIds = array_map(fn (Client $c) => $c->id, $clients);

        if (Orders::whereIn('id_clients', $clientIds)->exists()) {
            return; // pedidos de exemplo já criados
        }

        foreach (self::ORDERS as $i => [$clientIndex, $daysAgo, $status, $items]) {
            $client = $clients[$clientIndex];
            $date = Carbon::now()->subDays($daysAgo)->setTime(10 + $i % 9, ($i * 7) % 60);

            $total = 0;
            foreach ($items as [$title, $qty]) {
                $total += $products[$title]->price * $qty;
            }

            $order = Orders::forceCreate([
                'id_clients' => $client->id,
                'id_addresses' => $client->addresses()->value('id'),
                'status' => $status,
                'stock_decremented' => ! in_array($status, ['pendente', 'cancelado'], true),
                'total_value' => round($total, 2),
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            foreach ($items as [$title, $qty]) {
                OrderItems::forceCreate([
                    'id_order' => $order->id,
                    'id_product' => $products[$title]->id,
                    'title' => $title,
                    'price' => $products[$title]->price,
                    'quantity' => $qty,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            if ($status !== 'pendente') {
                Payments::forceCreate([
                    'id_orders' => $order->id,
                    'method' => $i % 2 ? 'pix' : 'cartao_credito',
                    'amount' => $order->total_value,
                    'status' => $status === 'cancelado' ? 'recusado' : 'aprovado',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
