<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;

class ListPostController extends Controller
{
    // optenemos la peticion con el request
    public function __invoke(Category $category = null, Request $request)
    {
        // optenemos los valores de la lista 'orden' y creamos 2 varibles separndo el array
        // devuelto por la funcion getListOrder()
        list($orderColumn, $ordenDirection) = $this->getListOrder($request->get('orden'));

        $posts = Post::orderBy($orderColumn, $ordenDirection)
            // usamos carga ambiciosa (Eager Loading)
            // psasamos los modelos o relaciones que queremos cargar
            ->with(['user', 'category'])
            // para optener los scope pending y completed del modelo Post usamos scopes
            ->scopes($this->getListScopes($category, $request))
            ->paginate()
            // mantenemos el orden en la paginacion por el campo orden
            ->appends($request->intersect(['orden']));

        return view('posts.index', compact('posts', 'category'));
    }

    public function getListScopes(Category $category, Request $request)
    {
        $scopes = [];

        // optenemos el nombre de la ruta en donde nos encontramos
        $routeName = $request->route()->getName();

        if ($category->exists){
            $scopes['category'] = [$category];
        }

        if ($routeName == 'posts.mine'){
            // agregamos al scopes el scope el condicional para los post propios
            $scopes['byUser'] = [$request->user()];
        }

        // si la ruta es posts.pending entonces agregamos al un valor al array de la consulta
        if ($routeName == 'posts.pending'){
            // agregamos al scopes el scope del status que va hacer pending
            $scopes[] = 'pending';
        }
        if ($routeName == 'posts.completed'){
            // agregamos al scopes el scope del status que va hacer completed
            $scopes[] = 'completed';
        }


        return $scopes;
    }

    protected function getListOrder($order)
    {
        if ($order == 'recientes'){
            return ['created_at' , 'desc'];
        }

        if ($order == 'antiguos'){
            return ['created_at' , 'asc'];
        }

        return ['created_at' , 'desc'];

    }
}
