<?php


use App\Comment;

class AcceptAnswerTest extends FeatureTestCase
{
    function test_the_posts_author_can_accept_a_comment_as_the_posts_answer()
    {
        // creamos un comentario el cual creará un post y un usuario el cual necesitaremos
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        // simulamos un login del usuario
        $this->actingAs($comment->post->user);

        // visitamos la pagina del post
        $this->visit($comment->post->url)
            ->press('Aceptar respuesta');

        // vemos el cambio en la base de datos aunque es de integracion no esta de mas hacerlo acá como complemento
        $this->seeInDatabase('posts', [
            'id' => $comment->post_id,
            'pending' => false,
            'answer_id' => $comment->id
        ]);

        // al presionar el boton deberiamos ser redirigidos a la url del post
        $this->seePageIs($comment->post->url)
            // deberiamos ver un elemento en donde tenga la clase .answer y el contenido sea el comentario
            // al cual le dimos click en aceptar respuesta
            ->seeInElement('.answer', $comment->comment);
    }
}
