<?php

namespace App\Http\Controllers;

use App\Token;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Token $token)
    {
        // autenticamos al usuario con el facade Auth
        Auth::login($token->user);

        // eliminamos el token despues de autenticar al usuario
        $token->delete();

        return redirect('/');
    }
}
