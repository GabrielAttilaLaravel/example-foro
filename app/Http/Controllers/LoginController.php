<?php

namespace App\Http\Controllers;

use App\Token;

class LoginController extends Controller
{
    public function login($token)
    {
        // hacemos una consulta para obtener el token
        $token = Token::findActive($token);

        // verificamos si es null el token
        if ($token == null){
            // mandamos un mensaje de error
            alert('Este enlace ya expiró, por favor solicita otro.', 'danger');

            // redirigimos para que pueda solicitar otro token
            return redirect()->route('token');
        }

        // autenticamos al usuario con el facade Auth y eliminamos el token despues de autenticar al usuario
        $token->login();

        return redirect('/');
    }
}
