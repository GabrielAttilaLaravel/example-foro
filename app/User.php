<?php

namespace App;

use App\Notifications\PostCommented;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // un usuario puede tener muchos post
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * un usuario puede tener muchos comentarios
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Post::class, 'subscriptions');
    }

    public function createPost(array $data)
    {
        $post = new Post($data);

        $this->posts()->save($post);

        $this->subscribeTo($post);

        return $post;
    }

    public function comment(Post $post, $message)
    {
        $comment = new Comment([
            'comment' => $message,
            'post_id' => $post->id,
        ]);

        $this->comments()->save($comment);

        // Notify subscribers
        // 1 - Subscriptores del post
        // 2 - La notificacion que queremos enviar
        //    1 - autor del comentario
        //    2 - el comentario
        Notification::send($post->subscribers()->where('users.id', '!=', $this->id)->get(),
            new PostCommented($this, $comment)
        );

        return $comment;
    }

    // verificamos si un usuario esta suscrito a un post en particular
    public function isSubscribedTo(Post $post)
    {
        return $this->subscriptions()->where('post_id', $post->id)->count() > 0;
    }

    public function subscribeTo(Post  $post)
    {
        $this->subscriptions()->attach($post);
    }

    public function unsubscribeFrom(Post  $post)
    {
        $this->subscriptions()->detach($post);
    }

    // owns: posee
    public function owns(Model $model)
    {
        // verificamos si un usuario es propietario de un modelo
        return $this->id === $model->user_id;
    }
}
