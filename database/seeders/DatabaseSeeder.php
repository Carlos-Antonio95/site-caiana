<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * O DemoSeeder (catálogo e pedidos de exemplo) não roda aqui:
     * php artisan db:seed --class=DemoSeeder
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);
    }
}
