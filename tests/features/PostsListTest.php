<?php

use App\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostsListTest extends FeatureTestCase
{
    function test_a_user_can_see_the_posts_list_and_go_to_the_detalis()
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

    function test_the_posts_are_paginated()
    {
        /** Having (Teniendo) **/
        // creamos un post personalizado
        $first = factory(Post::class)->create([
            'title' => 'Post más antiguo',
            // simulamos que este post fue creado hace 2 dias
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // generamos unos 15 posts con el metodo times()
        factory(Post::class)->times(15)->create([
            // simulamos que este post fue creado ayer
            'created_at' => Carbon::now()->subDay(),
        ]);

        // creamos un post personalizado
        $last = factory(Post::class)->create([
            'title' => 'Post más reciente',
            // simulamos que este post fue creado hoy
            'created_at' => Carbon::now(),
        ]);

        /** When (Cuando) **/
        $this->visit('/')
            ->see($last->title)
            ->dontSee($first->title)
            ->click('2')
            ->see($first->title)
            ->dontSee($last->title);
    }
}
