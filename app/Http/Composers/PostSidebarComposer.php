<?php
namespace App\Http\Composers;

use App\Category;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class PostSidebarComposer
{
    protected $listRoutes = ['posts.index', 'posts.completed', 'posts.pending'];

    // necesitamos asociar las vistar en los service provider
    public function compose(View $view)
    {
        $view->categoryItems = $this->getCategoryItems();

        $view->filters = trans('menu.filters');
    }

    protected function getCategoryItems()
    {
        $routeName = Route::getCurrentRoute()->getName();

        // verificamos que la ruta este en el listado
        if (!in_array($routeName, $this->listRoutes)){
            $routeName = 'posts.index';
        }

        // como get() nos devuelve una collection vamos a tomar cada categoria y la convertimos en un array
        return Category::orderBy('name')
            ->get()
            ->map(function ($category) use ($routeName){
                return [
                    'title' => $category->name,
                    'full_url' => route($routeName, $category)
                ];
            })->toArray();
    }
}