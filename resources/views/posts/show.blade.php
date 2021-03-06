@extends('layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>
    {{-- Escapamos el contenido antes de convertirlo en markdown con e() --}}
    {!! $post->convertToHtml($post->content) !!}

    <p>{{ $post->user->name }}</p>

    @if(auth()->check())
        @if(!auth()->user()->isSubscribedTo($post))
            {!! Form::open(['route' => ['posts.subscribe', $post], 'method' => 'POST']) !!}
                <button type="submit">Suscribirse al post</button>
            {!! Form::close() !!}
        @else
            {!! Form::open(['route' => ['posts.unsubscribe', $post], 'method' => 'DELETE']) !!}
                <button type="submit">Desuscribirse del post</button>
            {!! Form::close() !!}
        @endif
    @endif


    <h4>Comentarios</h4>

    {!! Form::open(['route' => ['comments.store', $post], 'method' => 'POST']) !!}

        {!! Field::textarea('comment') !!}

        <button type="submit">
            Publicar comentario
        </button>

    {!! Form::close() !!}

    @forelse($post->latestComments as $comment)
        <article class="{{ $comment->answer ? 'answer' : '' }}">

            {!! $comment->convertToHtml($comment->comment) !!}
            {{-- 1: can('accept', $comment)
                    antes de mostrar el formulario nos preguntamos:
                    si el usuario esta conectado aceptamos el comentario

                2: Gate::allow('accept', $comment)
                    Si el usuario puede aceptar el comentario y este comentario no esta ya marcado
                    como la respuesta del post entonces mostramos el formulario
            --}}
            @if(Gate::allows('accept', $comment) && !$comment->answer)
                {!! Form::open(['route' => ['comments.accept', $comment], 'method' => 'POST']) !!}
                    <button type="submit">Aceptar respuesta</button>
                {!! Form::close() !!}
            @endif
        </article>
    @empty
        Este post aun no tiene comentarios
    @endforelse

    @include('posts.sidebar')
@endsection