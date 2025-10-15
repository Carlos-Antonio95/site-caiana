<?php

return [

    'required'   => 'O campo :attribute é obrigatório.',
    'email'      => 'O campo :attribute deve ser um endereço de email válido.',
    'min'        => [
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
    ],
    'confirmed'  => 'A confirmação de :attribute não corresponde.',
    'unique'     => 'O :attribute já está em uso.',

    'attributes' => [
        'name'                  => 'nome',
        'email'                 => 'email',
        'phone'                 => 'telefone',
        'date_birth'            => 'data de nascimento',
        'password'              => 'senha',
        'password_confirmation' => 'confirmação de senha',
    ],

];
