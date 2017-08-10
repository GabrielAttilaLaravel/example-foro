<?php


use App\Post;

class PostModelTest extends TestCase
{
    function test_adding_a_title_generates_a_slug()
    {
        // creamos un post con eloquent
        $post = new Post([
           'title' => 'Como instalar Laravel'
        ]);
        // verificamos si tiene un slug
        $this->assertSame('como-instalar-laravel', $post->slug);
    }

    function test_editing_the_title_chenges_the_slug()
    {
        // creamos un post con eloquent
        $post = new Post([
            'title' => 'Como instalar Laravel'
        ]);
        // cambiamos el titulo
        $post->title = 'Como instalar Laravel 5.1 LTS';

        // verificamos si tiene un slug
        $this->assertSame('como-instalar-laravel-51-lts', $post->slug);
    }
}
