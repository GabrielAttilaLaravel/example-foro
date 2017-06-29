<?php

namespace App;

use App\Mail\TokenMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Mail};
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

    public static function findActive($token)
    {
       return static::where('token', $token)
                ->where('created_at', '>=', Carbon::parse('-30 minutes'))
                ->first();
    }

    public function sendByEmail()
    {
        Mail::to($this->user)->send(new TokenMail($this));
    }

    public function login()
    {
        // autenticamos al usuario con el facade Auth
        Auth::login($this->user);

        // eliminamos el token despues de autenticar al usuario
        $this->delete();
    }

    public function getUrlAttribute()
    {
        // devolvemos la url para iniciar sesion
        return route('login', ['token' => $this->token]);
    }
}
