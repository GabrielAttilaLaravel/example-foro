<?php

namespace App\Http\Controllers;

use App\{User, Token};
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register/create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users',
            'username' => 'required|min:5',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
        ]);

        $user = User::create($request->all());

        // generamos un token para el usuario y enviamos un email
        Token::generateFor($user)->sendByEmail();
    }

    public function confirmation()
    {
        return view('register/confirmation');
    }
}
