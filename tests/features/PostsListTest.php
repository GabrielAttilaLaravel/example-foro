<?php

use App\Category;
use App\Post;
use Carbon\Carbon;

class PostsListTest extends FeatureTestCase
{
    public function test_a_user_can_see_that_there_are_no_posts()
    {
        // visitamos los posts pendientes
        $this->visitRoute('posts.pending')
            // no deberiamos ver posts
            ->see('No hay post por los momentos');

        // visitamos los posts completados
        $this->visitRoute('posts.completed')
            // no deberiamos ver posts
            ->see('No hay post por los momentos');
    }

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

    function test_a_user_can_see_posts_filtered_by_category()
    {
        /**
         * Having
         */
        // creamos 2 categorias
        $laravel = factory(Category::class)->create([
            'name' => 'Laravel',
            'slug' => 'laravel'
        ]);

        $vue = factory(Category::class)->create([
            'name' => 'Vue.js',
            'slug' => 'vue-js'
        ]);

        // creamos un post para cada categoria
        $laravelPost = factory(Post::class)->create([
            'title' => 'Post de Laravel',
            'category_id' => $laravel->id
        ]);

        $vuePost = factory(Post::class)->create([
            'title' => 'Post de Vue.js',
            'category_id' => $vue->id
        ]);

        /**
         * When
         */
        $this->visit('/')
            ->see($laravelPost->title)
            ->see($vuePost->title)
            // hacemos click en el enlace Laravel q esta dentro del elemento .categories el cual es un select
            ->within('.categories', function (){
                $this->click('Laravel');
            })
            // esperamos ver en un elemento h1 'Posts de Laravel'
            ->seeInElement('h1', 'Posts de Laravel')
            // y tambien vemos el titulo del post de laravel
            ->see($laravelPost->title)
            // y ya no se deberia ver el titulo de vue.js
            ->dontSee($vuePost->title);
    }

    function test_a_user_can_see_its_own_posts(){
        // generamos un usuario y simulamos la coneccion
        $this->actingAs($user = $this->defaultUser());

        $userPost = $this->createPost([
            'title' => 'Post del usuario',
            'user_id' => $user->id,
        ]);

        // creamos un post que tendra como author un usuario aleatorio
        $anotherUserPost = $this->createPost([
            'title' => 'Post del otro usuario',
        ]);

        // cuando visitamos la pagina del listado de post
        $this->visitRoute('posts.index')
            // le damos click al boton "Mis posts"
            ->click('Mis posts')
            // deberiamos ver el primer posts  y no el 2do post creado
            ->see($userPost->title)
            ->dontSee($anotherUserPost->title);

    }

    function test_a_user_can_see_posts_filtered_by_status()
    {
        $pendingPost = factory(Post::class)->create([
            'title' => 'Post pendiente',
            'pending' => true
        ]);

        $completedPost = factory(Post::class)->create([
            'title' => 'Post competado',
            'pending' => false
        ]);

        // visitamos los posts pendientes
        $this->visitRoute('posts.pending')
            // deberiamos ver los posts pendientes
            ->see($pendingPost->title)
            // y no deberiamos ver los posts completados
            ->dontSee($completedPost)
            ->dontSee('No hay post por los momentos');

        // visitamos los posts completados
        $this->visitRoute('posts.completed')
            ->see($completedPost->title)
            ->dontSee($pendingPost->title)
            ->dontSee('No hay post por los momentos');
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
