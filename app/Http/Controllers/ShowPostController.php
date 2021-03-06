<?php

namespace App\Http\Controllers;

use App\Post;

class ShowPostController extends Controller
{
    public function __invoke(Post $post, $slug){


        // si el slug del post que obtenemos no es el mismo lo redirigimos a la nueva
        if ($post->slug != $slug){
            // redireccion permanente (301) es decir la url del post a cambiado
            return redirect($post->url, 301);
        }
        // retornamos la vista con la variable post
        return view('posts.show', compact('post'));
    }
}
