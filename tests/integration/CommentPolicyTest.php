<?php

use App\{Comment, User};
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentPolicyTest extends TestCase
{

    use DatabaseTransactions;

    function test_the_post_author_can_select_a_comment_as_an_answer()
    {
        // creamos un comentario
        $comment = factory(Comment::class)->create();

        // hacemos una instancia de las politicas para poder aceptar un post como respuesta
        $policy = new CommentPolicy;

        // el autor del post puede aceptar como respuesta un comentario del post
        $this->assertTrue(
            $policy->accept($comment->post->user, $comment)
        );
    }

    function test_non_authors_cannot_select_a_comment_as_an_answer()
    {
        // creamos un comentario
        $comment = factory(Comment::class)->create();

        // hacemos una instancia de las politicas para poder aceptar un post como respuesta
        $policy = new CommentPolicy;

        // el autor del post puede aceptar como respuesta un comentario del post
        $this->assertFalse(
            $policy->accept(factory(User::class)->create(), $comment)
        );
    }
}
