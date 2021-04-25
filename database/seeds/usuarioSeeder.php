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
            'email'             => "admin@hotmail.com",
            'email_verified_at' => null,
            'password'          => Hash::make('1234'),
            'imagen'            => "/",
            'remember_token'    => Str::random(60),
        ]);
    }
}
