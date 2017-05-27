<?php

use App\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_slug_is_generarted_and_saved_to_the_databse()
    {
        // creamos un user
        $user = $this->defaultUser([
            'name' => 'Gabriel Moreno'
        ]);

        // creamos un post pero aun no lo guardamos en la db (make)
        $post = factory(Post::class)->make([
            'title' => 'Como instalar Laravel',
        ]);

        // asignamos un author al post (se asigna automaricamente el user_id al post)
        $user->posts()->save($post);

        // guardamos el post
        $post->save();
        // verificamos si tiene un slug fresh(): trae un nuevo comodelo fresco con informacion de la db
        $this->assertSame('como-instalar-laravel', $post->fresh()->slug);
        /*
            // y lo tendriamos que visualizar en la db
            $this->seeInDatabase('posts', [
                'slug' => 'como-instañar-laravel'
            ]);
        */
    }
}
