<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // para evitar el error de asignacion masiva
    // Illuminate\Database\Eloquent\MassAssignmentException
    protected $fillable = ['title', 'content'];
}
