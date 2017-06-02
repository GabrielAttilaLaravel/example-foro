<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['comment', 'post_id'];

    // forsamos a un campo ser de tipo boolean
    protected $casts = [
        'answer' => 'boolean'
    ];

    public function post()
    {
        // un comentario pertenece a un post
        return $this->belongsTo(Post::class);
    }

    public function markAsAnswer()
    {
        // traigo los comentarios donde answer sea true y lo actualizo a false
        $this->post->comments()->where('answer', true)->update(['answer' => false]);

        $this->answer = true;

        $this->save();

        $this->post->pending = false;

        $this->post->save();
    }
}
