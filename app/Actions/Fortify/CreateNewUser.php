<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\Client;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
             'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => $this->passwordRules(),
            'phone'      => ['required', 'string', 'max:20'],
            'date_birth' => ['required', 'date'],
        ])->validate();
// Cria o usuário
        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // Cria o client vinculado
        Client::create([
            'id_users'   => $user->id,
            'full_name'  => $input['name'],
            'phone'      => $input['phone'],
            'date_birth' => $input['date_birth'],
        ]);

        return $user;
}
}