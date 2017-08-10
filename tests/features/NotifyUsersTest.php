<?php


use App\Notifications\PostCommented;
use App\User;
use Illuminate\Support\Facades\Notification;

class NotifyUsersTest extends FeatureTestCase
{

    function test_the_subscribers_recive_a_notification_when_post_is_commented()
    {
        // usaremos notificaciones falsas para las tests
        Notification::fake();

        // Having
        $post = $this->createPost();

        // usuario que se suscribe al post
        $subscriber = factory(User::class)->create();

        $subscriber->subscribeTo($post);

        // usuario que escribe un comentario en el post
        $writer = factory(User::class)->create();

        // suscribimos al usuario al post
        $writer->subscribeTo($post);

        $comment = $writer->comment($post, 'Un comentario cualquiera');

        // 1 - Usuario que recibe la notificacion
        // 2 - Nombre de la clase de la notificacion
        // 3 - Closer para comprobaciones personales
        Notification::assertSentTo(
            $subscriber, PostCommented::class, function ($notification) use ($comment){
                // verificacion si la notificacion pertenece al post
                return $notification->comment->id == $comment->id;
            }
        );

        // El autor del comentario no deberia recibir notificacion de su propio comentario
        Notification::assertNotSentTo($writer, PostCommented::class);
    }
}
