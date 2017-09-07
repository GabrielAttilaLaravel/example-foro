<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoteForPostTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_user_can_upvote_for_a_post()
    {
        // generamos un usuario y simulamos la coneccion
        $this->actingAs($user = $this->defaultUser());

        // creamos un post
        $post = $this->createPost();

        // enviamos una peticion tipo post con la url del post mas '/vote'
        $this->postJson($post->url . '/upvote')
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

    function test_a_user_can_downvote_for_a_post()
    {
        // generamos un usuario y simulamos la coneccion
        $this->actingAs($user = $this->defaultUser());

        // creamos un post
        $post = $this->createPost();

        // enviamos una peticion tipo post con la url del post mas '/vote'
        $this->postJson($post->url . '/downvote')
            // verificamos que la respeusta tiene un stado satisfactorio '200'
            ->assertSuccessful()
            // verificacmos que la respuesta contiene el fragmento JSON dado.
            ->assertJson([
                'new_score' => -1
            ]);

        // verificamos si hay cambio en una tabla espesifica en la DB
        $this->assertDatabaseHas('votes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'vote'    => -1
        ]);

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        $this->assertSame(-1, $post->fresh()->score);
    }

    function test_a_user_can_unvote_a_post()
    {
        // generamos un usuario y simulamos la coneccion
        $this->actingAs($user = $this->defaultUser());

        // creamos un post
        $post = $this->createPost();

        // creamos un voto
        // Vote::upvote($post);
        // cargamos el repositorio para crear el voto del post
        // app(voteRepository::class)->upvote($post);
        $post->upvote();

        // enviamos una peticion tipo post con la url del post mas '/vote'
        $this->deleteJson($post->url . '/vote')
            // verificamos que la respeusta tiene un stado satisfactorio '200'
            ->assertSuccessful()
            // verificacmos que la respuesta contiene el fragmento JSON dado.
            ->assertJson([
                'new_score' => 0
            ]);

        // verificamos si hay cambio en una tabla espesifica en la DB
        $this->assertDatabaseMissing('votes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        // agregamos una nueva columna de score al modelo de post el cual despues de llamar al metodo
        // upvote una sola vez deberia ser igual a "1"
        //$this->assertSame(0, $post->fresh()->score);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'score' => 0
        ]);
    }

    function test_a_guest_user_cannot_vote_for_a_post()
    {
        // generamos un usuario
        $user = $this->defaultUser();

        // creamos un post
        $post = $this->createPost();

        $this->postJson($post->url . '/upvote')
            // deberiamos ver un error 401 indiando que el usuario no tiene acceso
            ->assertStatus(401)
            ->assertJson(['error' => 'Unauthenticated.']);

        // verificamos si hay cambio en una tabla espesifica en la DB
        $this->assertDatabaseMissing('votes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'score' => 0
        ]);
    }
}