@extends('layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>
    {{-- Escapamos el contenido antes de convertirlo en markdown con e() --}}
    {!! $post->convertToHtml($post->content) !!}

    <p>{{ $post->user->name }}</p>

    <h4>Comentarios</h4>

    {!! Form::open(['route' => ['comments.store', $post], 'method' => 'POST']) !!}

        {!! Field::textarea('comment') !!}

        <button type="submit">
            Publicar comentario
        </button>

    {!! Form::close() !!}

    @forelse($post->latestComments as $comment)
        <article class="{{ $comment->answer ? 'answer' : '' }}">

            {{-- todo: support markadown in the comments as well --}}

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
@endsection