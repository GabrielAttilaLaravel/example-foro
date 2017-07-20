<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // optenemos la peticion con el request
    public function index(Category $category = null, Request $request)
    {
        $posts = Post::orderBy('created_at', 'DESC')
            ->scopes($this->getListScopes($category, $request))
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

    public function getListScopes(Category $category, Request $request)
    {
        $scopes = [];

        if ($category->exists){
            $scopes['category'] = [$category];
        }

        // optenemos el nombre de la ruta en donde nos encontramos
        $routeName = $request->route()->getName();

        // si la ruta es posts.pending entonces agregamos al un valor al array de la consulta
        if ($routeName == 'posts.pending'){
            // agregamos al scopes el scope del status que va hacer pending
            $scopes[] = 'pending';
        }elseif ($routeName == 'posts.completed'){
            // agregamos al scopes el scope del status que va hacer completed
            $scopes[] = 'completed';
        }

        return $scopes;
    }
}
