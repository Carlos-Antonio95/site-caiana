<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    /**
     * Cria (ou garante) o administrador definido em config/admin.php.
     * Pode rodar várias vezes: não duplica e não troca a senha existente.
     */
    public function run(): void
    {
        $email = config('admin.email');
        $password = config('admin.password');

        if (! $email || ! $password) {
            throw new RuntimeException('Defina ADMIN_EMAIL e ADMIN_PASSWORD no .env antes de rodar o AdminSeeder.');
        }

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => config('admin.name'),
                'password' => $password, // o cast 'hashed' do model aplica o hash
                'role' => 'admin',
            ]
        );

        if ($admin->role !== 'admin') {
            $admin->role = 'admin';
            $admin->save();
        }

        $this->command?->info("Admin pronto: {$admin->email}");
    }
}
