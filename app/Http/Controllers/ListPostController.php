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
            // asignamos la categoria
            ->category($category)
            // llamamos al metodo getRouteScope para unicializar la variable scope para los condicionales
            // pending y completed del modelo
            ->scopes($this->getRouteScope($request))
            ->paginate()
            // mantenemos el orden en la paginacion por el campo orden
            ->appends($request->intersect(['orden']));

        return view('posts.index', compact('posts', 'category'));
    }

    protected function getRouteScope(Request $request)
    {
        $scopes = [
            // agregamos al scopes el scope el condicional para los propios post
            'posts.mine' => ['byUser' => [$request->user()]],
            // agregamos al scopes el scope del status que va hacer pending
            'posts.pending' => ['pending'],
            // agregamos al scopes el scope del status que va hacer completed
            'posts.completed' => ['completed']
        ];

        // verificamos si la ruta tiene un scope asociado de lo contrario retornamos un array vacio
        // $request->route()->getName() = optenemos el nombre de la ruta en donde nos encontramos
        return $scopes[$request->route()->getName()] ?? []; // php7 operador ternario
        // return isset($scopes[$name]) ? $scopes[$name] : [];
    }

    protected function getListOrder($order)
    {
        $orders = [
            'recientes' => ['created_at' , 'desc'],
            'antiguos'  => ['created_at' , 'asc'],
        ];

        return $orders[$order] ?? ['created_at' , 'desc']; // php7 operador ternario
    }
}
