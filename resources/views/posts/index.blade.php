@extends('layouts.app')

@section('content')
    <h1>
        {{ $category->exists ? 'Posts de '.$category->name : 'Posts' }}

    </h1>

    <div class="row">
        <div class="col-md-2">
            <h4>Filtros</h4>
                {!! Menu::make('menu.filters', 'nav filters') !!}
            <h4>Categoría</h4>
                {!! Menu::make($categoryItems, 'nav categories') !!}
        </div>
        <div class="col-md-10">
            @each('posts.item', $posts, 'post', 'posts.itemEmpty')
                {{--@forelse($posts as $post)
                    @include('posts.item', compact('$post'))
                @empty
                    No hay post por los momentos
                @endforelse--}}
            {{ $posts->render() }}
        </div>
    </div>
@endsection