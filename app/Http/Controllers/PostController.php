<?php

namespace App\Http\Controllers;

use App\Post;

class PostController extends Controller
{
    public function show(Post $post){
        // retornamos la vista con la variable post
        return view('posts.show', compact('post'));
    }
}
