@extends('layouts.app')

@section('content')
    <h1>
        {{ $category->exists ? 'Posts de '.$category->name : 'Posts' }}

    </h1>

    <ul>
        @forelse($posts as $post)
            <li>
                <a href="{{ $post->url }}">{{ $post->title }}</a>
            </li>
        @empty
            No hay post por los momentos
        @endforelse
    </ul>

    {{ $posts->render() }}

    {{-- creamos un menu de categorias con styde --}}
    {!! Menu::make($categoryItems, 'nav categories') !!}

@endsection