<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    // desactivamos el $guarded que da error de asignacion masiva (MassAssignmentException)
    // lo podemos hacer ya que no vamos a llamar las funciones de este modelo en el controlador
    // ej: Vote::create($request->all())
    protected $guarded = [];

    public static function upvote(Post $post)
    {
        static::addVote($post , 1);
    }

    public static function downVote(Post $post)
    {
        static::addVote($post , -1);
    }

    protected static function addVote(Post $post, $amount)
    {
        // creamos o actualizamos el post
        // 1 - condicional para comprobar si existe o no el voto
        // 2 - valor hacer por el cual va hacer creado o acualizado
        static::updateOrCreate(
            ['post_id' => $post->id, 'user_id' => auth()->id()],
            ['vote' => $amount]
        );

        // Actualizamos el scrore del post
        static::refreshPostScore($post);


    }

    public static function undoVote(Post $post)
    {
        // queremos que el voto coincida con el post y con el usuario que esta conectado para
        // poder eliminarlo
        static::where([
            'post_id' => $post->id,
            'user_id' => auth()->id()
        ])->delete();

        // Actualizamos el scrore del post
        static::refreshPostScore($post);
    }

    /**
     * Actualizamos el scrore del post
     *
     * @param Post $post
     */
    protected static function refreshPostScore(Post $post)
    {
        // calcular el score del post sumando el total de votos de cada uno de los post
        $post->score = static::where(['post_id' => $post->id])->sum('vote');

        $post->save();
    }
}
