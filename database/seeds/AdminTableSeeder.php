<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'email' => 'gabrieljmorenot@gmail.com',
            'username' => 'gabrielattila',
            'first_name' => 'Gabriel',
            'last_name' => 'Moreno',
            'role' => 'admin',
        ]);
    }
}
