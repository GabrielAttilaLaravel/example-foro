<?php


use App\{Comment, User};

class AcceptAnswerTest extends FeatureTestCase
{
    function test_the_posts_author_can_accept_a_comment_as_the_posts_answer()
    {
        // creamos un comentario el cual creará un post y un usuario el cual necesitaremos
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        // simulamos un token del usuario
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

    function test_non_the_posts_author_dont_see_the_accept_answer_button()
    {
        // creamos un comentario el cual creará un post y un usuario el cual necesitaremos
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        // simulamos un token del usuario
        $this->actingAs(factory(User::class)->create());

        // visitamos la pagina del post y no deberiamos ver el boton de Aceptar respuesta
        $this->visit($comment->post->url)
            ->dontSee('Aceptar respuesta');
    }

    function test_non_the_posts_author_can_accept_a_comment_as_the_posts_answer()
    {
        // creamos un comentario el cual creará un post y un usuario el cual necesitaremos
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        // simulamos un token del usuario
        $this->actingAs(factory(User::class)->create());

        // enviamos un comentario a la url accept del post directamente asi el boton no se este viendo
        $this->post(route('comments.accept', $comment));

        // vemos el cambio en la base de datos aunque es de integracion no esta de mas hacerlo acá como complemento
        $this->seeInDatabase('posts', [
            'id' => $comment->post_id,
            'pending' => true,
        ]);
    }

    function test_the_accept_button_is_hiddend_when_the_comment_is_already_the_posts_answer()
    {
        // creamos un comentario el cual creará un post y un usuario el cual necesitaremos
        $comment = factory(Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        // simulamos un token del usuario
        $this->actingAs($comment->post->user);

        // marcamos el comentario como respuesta del post
        $comment->markAsAnswer();

        // visitamos la pagina del post y no deberiamos ver el boton de Aceptar respuesta
        $this->visit($comment->post->url)
            ->dontSee('Aceptar respuesta');
    }
}
