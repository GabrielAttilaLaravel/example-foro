<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    // desactivamos el $guarded que da error de asignacion masiva (MassAssignmentException)
    // lo podemos hacer ya que no vamos a llamar las funciones de este modelo en el controlador
    // ej: Vote::create($request->all())
    protected $guarded = [];


}
