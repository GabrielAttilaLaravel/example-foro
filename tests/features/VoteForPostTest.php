<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoteForPostTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_user_can_voted_for_a_post()
    {
        // generamos un usuario y simulamos la coneccion
        $this->actingAs($user = $this->defaultUser());

        // creamos un post
        $post = $this->createPost();

        // enviamos una peticion tipo post con la url del post mas '/vote'
        $this->postJson($post->url . '/vote')
            // verificamos que la respeusta tiene un stado satisfactorio '200'
            ->assertSuccessful()
            // verificacmos que la respuesta contiene el fragmento JSON dado.
            ->assertJson([
                'new_score' => 1
            ]);

        // verificamos si hay cambio en una tabla espesifica en la DB
        $this->assertDatabaseHas('votes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'vote'    => 1
        ]);

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(1, $post->fresh()->score);
    }
}