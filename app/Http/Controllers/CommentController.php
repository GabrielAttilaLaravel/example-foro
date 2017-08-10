<?php

namespace App\Http\Controllers;

use App\{Comment, Post};
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        auth()->user()->comment($post, $request->get('comment'));

        return redirect($post->url);
    }

    public function accept(Comment $comment)
    {
        // implementamos la politica de acceso para que aun si el boton no se muestre no puedan
        // modificar colocar el post como respeusta
        // (deja pasar a los usuarios que estan autorizados para aceptar este comentario)
        $this->authorize('accept', $comment);

        $comment->markAsAnswer();

        return redirect($comment->post->url);
    }
}
