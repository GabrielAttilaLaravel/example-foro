<?php

namespace App;

use App\Mail\TokenMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    // desactivamos la option de carga masiva
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // le indicamos a eloquent que el atributo que va  a utilizar para generar la url va hacer el token y no el id
    public function getRouteKeyName()
    {
        return 'token';
    }

    public static function generateFor(User $user)
    {
        $token = new static;

        $token->token = str_random(60);

        // associate de la relacion belongsTo de eloquent
        $token->user()->associate($user);

        $token->save();

        return $token;
    }

    public function sendByEmail()
    {
        Mail::to($this->user)->send(new TokenMail($this));
    }
}
