<?php

namespace Tests\Browser;

use App\Post;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\traits\TestsHelper;

class CreatePostTest extends DuskTestCase
{
    use DatabaseMigrations, TestsHelper;

    protected $title = 'Esta es una pregunta';
    protected $content = 'Este es el contenido';

    public function test_a_user_create_a_post()
    {
        $user = $this->defaultUser();

        $this->browse(function (Browser $browser) use ($user){
            /** Having (Teniendo) **/
            // generamos un usuario y simulamos la coneccion
            $browser->loginAs($user)
                /** When (Cuando) **/
                // visitamos la ruta posts.create
                ->visitRoute('posts.create')
                // ingresamos el siguiente texto en campo title
               ->type('title', $this->title)
                // ingresamos el siguiente texto en campo content
                ->type('content', $this->content)
                // presionamos el boton publicar
                ->press('Publicar')
                /**
                 * Test a user is redirected to the posts details after creating it.
                 * (Prueba que un usuario se redirige a los detalles del post después de crearlo).
                **/
                ->assertPathIs('/posts/1-esta-es-una-pregunta')
                ->logout();
        });

        /** Then (Entonces) **/
        // Deberiamos ver en la db en la tabla post lo siguiente:
        $this->assertDatabaseHas('posts', [
            'title' => $this->title,
            'content' => $this->content,
            'pending' => true,
            'user_id' => $user->id,
            'slug' => 'esta-es-una-pregunta',
        ]);

        $post = Post::first();

        // Test the author is suscribed automatizally ti the post.
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }

    function test_creating_a_post_requires_authentication()
    {
        $this->browse(function (Browser $browser){
            /** When (Cuando) **/
            // visitamos la ruta posts.create
           $browser->visitRoute('posts.create')
               /** Then (Entonces) **/
               // si el usuario no esta logeado lo redirecciona a la pagina de token
               ->assertPathIs('/token');
        });
    }


    public function test_create_post_form_validation()
    {
        $this->browse(function (Browser $browser){
            // generamos un usuario y simulamos la coneccion
            $browser->loginAs($this->defaultUser())
                // una ves conectados visitamos la ruta para crear el post
                ->visitRoute('posts.create')
                // presionamos el boton publicar
                ->press('Publicar')
                // deberiamos ver que la pagina aun es la misma
                ->assertPathIs('/posts/create')
                // y deberiamos ver el elemento siguiente con el mensaje de ayuda
                ->assertSeeErrors([
                    'title' => 'El campo título es obligatorio',
                    'content' => 'El campo contenido es obligatorio'
                ]);
        });
    }
}
