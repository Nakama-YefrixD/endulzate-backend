<?php

use Illuminate\Database\Seeder;
use App\User;

class usuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username'          => "Administrador",
            'name'              => "Administrador",
            'email'             => "Administrador@hotmail.com",
            'email_verified_at' => null,
            'password'          => Hash::make('gg$$'),
            'imagen'            => "/",
            'remember_token'    => Str::random(60),
        ]);

        User::create([
            'username'          => "Endulzate1",
            'name'              => "Endulzate1",
            'email'             => "Endulzate1@hotmail.com",
            'email_verified_at' => null,
            'password'          => Hash::make('1234'),
            'imagen'            => "/",
            'remember_token'    => Str::random(60),
        ]);

        User::create([
            'username'          => "Endulzate2",
            'name'              => "Endulzate2",
            'email'             => "Endulzate2@hotmail.com",
            'email_verified_at' => null,
            'password'          => Hash::make('1234'),
            'imagen'            => "/",
            'remember_token'    => Str::random(60),
        ]);
    }
}
