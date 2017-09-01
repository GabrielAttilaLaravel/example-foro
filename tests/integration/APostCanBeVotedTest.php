<?php

use App\Vote;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class APostCanBeVotedTest extends TestCase
{
    use DatabaseTransactions;

    // un post puede tener un punto positivo
    function test_a_post_can_be_unvoted(){

        // generamos un usuario y simulamos la coneccion
        $this->actingAs($user = $this->defaultUser());


        $post = $this->createPost();

        // llamamos a la funcion upvote para poder votar por le post
        Vote::upvote($post);

        // verificamos si hay cambio en una tabla espesifica en la DB
        $this->assertDatabaseHas('votes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'vote'    => 1
        ]);

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(1, $post->score);
    }
}