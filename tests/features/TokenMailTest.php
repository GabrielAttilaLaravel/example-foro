<?php


use App\Mail\TokenMail;
use App\{User, Token};
use App\Traits\InteractsWithMailable;


class TokenMailTest extends FeatureTestCase
{
    use InteractsWithMailable;

    function test_it_sends_a_links_with_the_token()
    {
        $user = new User([
            'first_name' => 'Gabriel',
            'last_name' => 'Moreno',
            'email' => 'gabrieljmorenot@gmail.com'
        ]);

        // creamos un token
        $token = new Token([
            'token' => 'this_is_a_token',
            'user' => $user,
        ]);

        // usamos el metodo open del trait en el cual pasamos una instancia del metodo que queremos enviar
        $this->open(new TokenMail($token))
            //dd($message->getBody());
            // vemos el link deseado
            ->seeLink($token->url, $token->url);
    }


}
