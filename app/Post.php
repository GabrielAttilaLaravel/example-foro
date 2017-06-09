<?php

namespace App;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    // para evitar el error de asignacion masiva
    // Illuminate\Database\Eloquent\MassAssignmentException
    protected $fillable = ['title', 'content'];

    // forsamos a un campo ser de tipo boolean
    protected $casts = [
        'pending' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // un post puede tener muchos comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // llamamos una relacion dentro de otra relacion
    public function latestComments()
    {
        return $this->comments()->orderBy('created_at', 'DESC');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        $this->attributes['slug'] = Str::slug($value);
    }

    public function getUrlAttribute()
    {
        return route('posts.show', [$this->id, $this->slug]);
    }

    public function getSafeHtmlContentAttribute()
    {
        return Markdown::convertToHtml(e($this->content));
     }
}
