<?php


use App\Mail\TokenMail;
use App\Token;
use Illuminate\Support\Facades\Mail;

class RequestTokenTest extends FeatureTestCase
{
    function test_a_user_guest_can_request_a_token()
    {
        // Having
        Mail::fake();

        $user = $this->defaultUser(['email' => 'admin@gtec.net']);

        // When
        $this->visitRoute('token')
            ->type('admin@gtec.net', 'email')
            ->press('Solicitar token');

        // Then: a new token is created in the database
        $token = Token::where('user_id', $user->id)->first();

        // comrpobamos que el token no es null
        $this->assertNotNull($token, 'A token was not created.');

        // comprobamos que el usuario recibe el token
        // 1 - el nombre de la clase que estamos usando para enviar el email
        // 2 - closer para verificar el envio del token
        Mail::assertSent(TokenMail::class, function ($mail) use ($token, $user){
            return $mail->hasTo($user) && $mail->token->id == $token->id;
        });

        // verificamos que el usuario aun no a iniciado sesion hasta que le click a la url enviada al email
        $this->dontSeeIsAuthenticated();

        $this->see('Enviamos a tu email un enlace para que inicies sesión');
    }

    function test_a_user_guest_can_request_a_token_without_an_email()
    {
        // Having
        Mail::fake();

        // When
        $this->visitRoute('token')
            ->press('Solicitar token');

        // Then: a new token is NOT created in the database
        $token = Token::first();

        $this->assertNull($token, 'A token was created.');

        // comprobamos que el usuario recibe el token
        // 1 - A quien le vamos a enviar el email
        // 2 - el nombre de la clase que esamos usando para enviar el email
        // 3 - closer para verificar el envio del token
        Mail::assertNotSent(TokenMail::class);

        // verificamos que el usuario aun no a iniciado sesion hasta que le click a la url enviada al email
        $this->dontSeeIsAuthenticated();

        $this->seeErrors([
            'email' => 'El campo correo electrónico es obligatorio.'
        ]);
    }

    function test_a_user_guest_can_request_a_token_an_invalid_email()
    {
        $this->visitRoute('token')
            ->type('Attila', 'email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'correo electrónico no es un correo válido'
        ]);
    }

    function test_a_user_guest_can_request_a_token_with_a_non_existent_email()
    {
        // Having
        $this->defaultUser(['email' => 'admin@gtec.net']);

        // When
        $this->visitRoute('token')
            ->type('attila@gtec.net', 'email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Este correo electrónico no existe'
        ]);
    }
}
