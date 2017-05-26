<?php

class CreatePostsTest extends FeatureTestCase
{
    function test_a_user_create_a_post()
    {
        /** Having (Teniendo) **/
        $title = 'Esta es una pregunta';
        $content = 'Este es el contenido';

        // generamos un usuario y simulamos la coneccion
        $this->actingAs($user = $this->defaultUser());

        /** When (Cuando) **/
        // visitamos la ruta posts.create
        $this->visit('posts/create')
            // ingresamos el siguiente texto en campo title
            ->type($title, 'title')
            // ingresamos el siguiente texto en campo content
            ->type($content, 'content')
            // presionamos el boton publicar
            ->press('Publicar');

        /** Then (Entonces) **/
        // Deberiamos ver en la db en la tabla post lo siguiente:
        $this->seeInDatabase('posts', [
            'title' => $title,
            'content' => $content,
            'pending' => true,
            'user_id' => $user->id,
        ]);

        /**
         * Test a user is redirected to the posts details after creating it.
         * (Prueba de un usuario se redirige a los detalles del post después de crearlo).
         **/
        $this->see($title);
    }

    function test_creating_a_post_requires_authentication()
    {
        /** When (Cuando) **/
        // visitamos la ruta posts.create
        $this->visit('posts/create')
        /** Then (Entonces) **/
        // si el usuario no esta logeado lo redirecciona a la pagina de login
            ->seePageIs(route('login'));
    }


    public function test_create_post_form_validation()
    {
        // generamos un usuario y simulamos la coneccion
        $this->actingAs($user = $this->defaultUser())
            // una ves conectados visitamos la ruta para crear el post
            ->visit(route('posts.create'))
            // presionamos el boton publicar
            ->press('Publicar')
            // deberiamos ver que la pagina aun es la misma
            ->seePageIs(route('posts.create'))
            // y deberiamos ver el elemento siguiente cin el mensaje de ayuda
            ->seeInElement('#field_title.has-error .help-block', 'El campo título es obligatorio')
            ->seeInElement('#field_content.has-error .help-block', 'El campo contenido es obligatorio')
        ;
    }
}