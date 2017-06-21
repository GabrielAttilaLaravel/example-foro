<?php

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
            'first_name' => 'Gabriel',
            'last_name' => 'Moreno'
        ]);

        // creamos un post pero aun no lo guardamos en la db (make)
        $post = $this->createPost([
            'title' => 'Este es el titulo del post',
            'content' => 'Este es el contenido del post',
            'user_id' => $user->id
        ]);

        // asignamos un author al post (se asigna automaricamente el user_id al post)
        $user->posts()->save($post);

        /** When (Cuando) **/
        // visitamos una pagina
        $this->visit($post->url)
            // vemos en un elemento <h1> el titulo del post
            ->seeInElement('h1', $post->title)
            // tambien tendriamos que ver el contenido del post
            ->see($post->content)
            // y finalmente veriamos el author del post
            ->see('Gabriel Moreno');

    }

    // redirigimos las url viejas a las nuevas
    function test_old_urls_are_redirected()
    {
        /** Having (Teniendo) **/

        // creamos un user
        //$user = $this->defaultUser();

        // creamos un post pero aun no lo guardamos en la db (make)
        $post = $this->createPost([
            'title' => 'Old title',
        ]);

        // asignamos un author al post (se asigna automaricamente el user_id al post)
        //$user->posts()->save($post);

        // optenemos la url del post
        $url = $post->url;

        // cambiamos en algun punto el titulo
        $post->update(['title' => 'New title']);

        // visitamos la pagina
        $this->visit($url)
            ->seePageIs($post->url);
    }

/**
    function test_post_url_with_wrong_slugs_still_work()
    {
        /** Having (Teniendo)

        // creamos un user
        $user = $this->defaultUser();

        // creamos un post pero aun no lo guardamos en la db (make)
        $post = factory(Post::class)->make([
            'title' => 'Old title',
        ]);

        // asignamos un author al post (se asigna automaricamente el user_id al post)
        $user->posts()->save($post);

        // optenemos la url del post
        $url = $post->url;

        // cambiamos en algun punto el titulo
        $post->update(['title' => 'New title']);

        // visitamos la pagina
        $this->visit($url)
            // coomprobamos que el status de la url sea 200
            ->assertResponseOk()
            ->see('New title');
    }
    **/
}
