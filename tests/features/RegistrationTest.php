<?php

use App\{User, Token};
use App\Mail\TokenMail;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{
    function test_a_user_can_create_an_account()
    {
        Mail::fake();

        $this->visitRoute('register')
            ->type('admin@gtec.net', 'email')
            ->type('attila', 'username')
            ->type('Gabriel', 'first_name')
            ->type('Moreno', 'last_name')
            ->press('Registrate');

        $this->seeInDatabase('users', [
            'email' => 'admin@gtec.net',
            'username' => 'attila',
            'first_name' => 'Gabriel',
            'last_name' => 'Moreno'
        ]);

        // como no usamos password en el registro, le asignamos un token y se lo enviamos por correo
        $user = User::first();

        $this->seeInDatabase('tokens', [
            'user_id' => $user->id
        ]);

        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token);

        // comprobamos que el usuario recibe el token
        // 1 - el nombre de la clase que esamos usando para enviar el email
        // 2 - closer para verificar el envio del token
        Mail::assertSent(TokenMail::class, function ($mail) use ($token, $user){
            return $mail->hasTo($user) && $mail->token->id == $token->id;
        });

        $this->visitRoute('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Enviamos a tu email un enlace para que inicies sesión');
    }

    public function test_create_user_form_validation()
    {
        $this->visitRoute('register')
            // presionamos el boton Registrate
            ->press('Registrate')
            // deberiamos ver que la pagina aun es la misma
            ->seePageIs(route('register'))
            // y deberiamos ver el elemento siguiente con el mensaje de ayuda
            ->seeErrors([
                'email' => 'El campo correo electrónico es obligatorio',
                'username' => 'El campo usuario es obligatorio',
                'first_name' => 'El campo nombre es obligatorio',
                'last_name' => 'El campo apellido es obligatorio',
            ]);
    }
}
