<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends FeatureTestCase
{
    function test_basic_example()
    {
        // creamos un usuario
        $user = factory(\App\User::class)->create([
            'name' => 'Gabriel Moreno',
            'email' => 'gabrieljmorenot@gmail.com'
        ]);
        // iniciamos sesion pasandole el usuario creado y como 2do param el driver por el cual quiero autenticar
        $this->actingAs($user, 'api')
            ->visit('api/user')
             ->see('Gabriel Moreno gabrieljmorenot@gmail.com');
    }
}
