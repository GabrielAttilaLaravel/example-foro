<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    // usamos el trait DatabaseTransactions para que se eejcute dentro de una transaccion
    // y que la db siempre este vacia
    use DatabaseTransactions;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->assertTrue(false);

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
