<?php

use Illuminate\Support\Facades\Notification;

class WriteCommentTest extends FeatureTestCase
{
    function test_a_user_can_write_a_comment()
    {
        // colocamos esta notificacion para no dispara notificaciones reales al momento de crear un comentario
        Notification::fake();

        // creamos un post
        $post = $this->createPost();

        // creamos un user y simulamos la coneccion
        $user = $this->defaultUser();

        // simulamos la coneccion del user
        $this->actingAs($user)
            // visitamos la url del post
            ->visit($post->url)
            // escribimos un comentario en el campo comment
            ->type('Un comentario', 'comment')
            // presionamos el boton publicar comentario
            ->press('Publicar comentario');

        // deberiamos ver en la base de datos el comentario, el post asociado y el autor al cual pertenece
        $this->seeInDatabase('comments', [
            'comment' => 'Un comentario',
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        // despues que el usuario publique el comentario y este en la Database
        // lo redirigiremos a la url del post
        $this->seePageIs($post->url);
    }
}
