<?php

namespace App;

use Illuminate\Support\Str;

class Post extends Model
{
    // para evitar el error de asignacion masiva
    // Illuminate\Database\Eloquent\MassAssignmentException
    protected $fillable = ['title', 'content', 'category_id'];

    // forsamos a un campo ser de tipo boolean
    protected $casts = [
        'pending' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // un post puede tener muchos comentarios
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    // llamamos una relacion dentro de otra relacion
    public function latestComments()
    {
        return $this->comments()->orderBy('created_at', 'DESC');
    }

    public function scopeCategory($query, Category $category)
    {
        if ($category->exists){
            $query->where('category_id', $category->id);
        }
    }

    public function scopePending($query)
    {
        $query->where('pending', true);
    }

    public function scopeCompleted($query)
    {
        $query->where('pending', false);
    }

    public function scopeByUser($query, User $user)
    {
        $query->where('user_id', $user->id);
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


}
