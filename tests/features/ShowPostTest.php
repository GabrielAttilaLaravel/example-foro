<?php

use App\Post;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowPostTest extends FeatureTestCase
{
    /**
     *
     */
    public function test_a_user_can_see_the_post_details()
    {
        /** Having (Teniendo) **/

        // creamos un user
        $user = $this->defaultUser([
            'name' => 'Gabriel Moreno'
        ]);

        // creamos un post pero aun no lo guardamos en la db (make)
        $post = factory(Post::class)->make([
            'title' => 'Este es el titulo del post',
            'content' => 'Este es el contenido del post'
        ]);

        // asignamos un author al post (se asigna automaricamente el user_id al post)
        $user->posts()->save($post);

        /** When (Cuando) **/

        // visitamos una pagina
        $this->visit(route('posts.show', $post))
            // vemos en un elemento <h1> el titulo del post
            ->seeInElement('h1', $post->title)
            // tambien tendriamos que ver el contenido del post
            ->see($post->content)
            // y finalmente veriamos el author del post
            ->see($user->name);

    }
}
