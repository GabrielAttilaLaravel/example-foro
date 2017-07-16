<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;

class PostController extends Controller
{
    public function index(Category $category = null)
    {
        $posts = Post::orderBy('created_at', 'DESC')
            ->category($category)
            ->paginate();

        $categoryItems = $this->getCategoryItems();

        return view('posts.index', compact('posts', 'category' , 'categoryItems'));
    }
    
    public function show(Post $post, $slug){

        // si el slug del post que obtenemos no es el mismo lo redirigimos a la nueva
        if ($post->slug != $slug){
            // redireccion permanente (301) es decir la url del post a cambiado
            return redirect($post->url, 301);
        }

        // retornamos la vista con la variable post
        return view('posts.show', compact('post'));
    }

    protected function getCategoryItems()
    {
        // como get() nos devuelve una collection vamos a tomar cada categoria y la convertimos en un array
        return Category::orderBy('name')->get()->map(function ($category){
            return [
                'title' => $category->name,
                'full_url' => route('posts.index', $category)
            ];
        })->toArray();
    }
}
