<?php

namespace App\Policies;

use App\{Comment, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    // debemos registrar esta politica de acceso en el AuthServiceProvider
    // y usarla en el blade
    public function accept(User $user, Comment $comment)
    {
        // verificar si un usuario es propietario de un post y si el comentario no es la respuesta
        return $user->owns($comment->post) /*&& !$comment->answer*/;

        //return $user->id === $comment->post->user_id;
    }
}
