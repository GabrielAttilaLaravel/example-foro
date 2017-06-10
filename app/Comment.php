<?php

namespace App;

class Comment extends Model
{
    protected $fillable = ['comment', 'post_id'];


    public function post()
    {
        // un comentario pertenece a un post
        return $this->belongsTo(Post::class);
    }

    public function markAsAnswer()
    {
        $this->post->pending = false;
        // guardamos el id del comentario como respuesta del post
        $this->post->answer_id = $this->id;

        $this->post->save();
    }

    public function getAnswerAttribute()
    {
        return $this->id === $this->post->answer_id;
    }


}
