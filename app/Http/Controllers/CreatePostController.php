<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;

class CreatePostController extends Controller
{
    public function create()
    {
        // usamos el metodo pluck para traer ciertos campos de la categoria y lo convertimos en un array
        $categories = Category::pluck('name', 'id')->toArray();

        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        $post = auth()->user()->createPost($request->all());

        return redirect($post->url);
    }
}
