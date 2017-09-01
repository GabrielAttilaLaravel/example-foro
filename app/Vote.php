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
        static::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'vote'    => 1
        ]);

        $post->score = 1;
        $post->save();
    }
}
