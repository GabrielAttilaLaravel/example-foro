<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostsListTest extends FeatureTestCase
{
    public function test_a_user_can_see_the_posts_list_and_go_to_the_detalis()
    {
        /** Having (Teniendo) **/
        // creamos un post
        $post = $this->createPost([
           'title' => '¿Debo usar Laravel 5.3 o 5.1 LST?'
        ]);

        /** When (Cuando) **/
        // visitamos la ruta del home
        $this->visit('/')
            // vemos el elemento <h1>Posts</h1>
            ->seeInElement('h1','Posts')
            // vemos el titulo del post
            ->see($post->title)
            // y podremos darle click al titulo
            ->click($post->title)
            // al hacer click nos llevará a al post
            ->seePageIs($post->url);
    }
}
