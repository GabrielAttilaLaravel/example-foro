<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MarkCommentAsAnswerTest extends TestCase
{

    use DatabaseTransactions;

    function test_a_post_can_be_answered()
    {
        // creamos un post
        $post = $this->createPost();

        // creamos un comentario asociado a un post
        $comment = factory(\App\Comment::class)->create([
            'post_id' => $post->id
        ]);

        // llamamos a una funcion para que marque una respuesta como verdadera
        $comment->markAsAnswer();

        // corroboramos que devuelba verdadero
        $this->assertTrue($comment->fresh()->answer);

        // y verificamos que el post ya no esta como pendiente
        $this->assertFalse($post->fresh()->pending);
    }

    function test_a_post_can_only_one_answered()
    {
        // creamos un post
        $post = $this->createPost();

        // creamos 2 comentarios asociados a un post
        $comments = factory(\App\Comment::class)->times(2)->create([
            'post_id' => $post->id
        ]);

        // llamamos a una funcion para que marque una respuesta como verdadera
        $comments->first()->markAsAnswer();
        $comments->last()->markAsAnswer();

        // y verificamos que el post ya no esta como pendiente
        $this->assertFalse($comments->first()->fresh()->answer);

        // corroboramos que devuelba verdadero
        $this->assertTrue($comments->last()->fresh()->answer);

    }
}
