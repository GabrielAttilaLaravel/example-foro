<?php


use App\{
    Mail\TokenMail, User, Token
};
use Illuminate\Support\Facades\Mail;
use Symfony\Component\DomCrawler\Crawler;

class TokenMailTest extends FeatureTestCase
{


    // probamos si envía un enlace con el token
    function test_it_sends_a_link_with_the_token()
    {
        $user = new User([
            'first_name' => 'Gabriel',
            'last_name' => 'Moreno',
            'email' => 'attila@gtec.net'
        ]);

        $token = new Token([
            'token' => 'this-is-a-token',
            'user' => $user
        ]);

        // llamamos a la funcion open y le enviamos una instancia del mailable que queremos enviar
        $this->open(new TokenMail($token))
            // Afirmamos que un enlace dado se ve en la página.
            // pasamos el string y luego el enlace que esperamos ver
            ->seeLink($token->url, $token->url);
    }

    protected function open(\Illuminate\Mail\Mailable $mailable)
    {
        // optenemos el transporte de SwiftMailer el cual es por donde vamos a enviar el email de prueba
        $transport = Mail::getSwiftMailer()->getTransport();

        // eliminamos los mensajes de la coleccion, creando una nueva coleccion y reemplazando
        // la propiedad messages
        $transport->flush();

        // enviamos el email con una instancia d el mailable
        Mail::send($mailable);

        // optenemos el primer mensaje dentro de la colleccion de mensajes
        $message = $transport->messages()->first();

        // un crawler rastrea las páginas webs en busca de enlaces
        $this->crawler = new Crawler($message->getBody());

        return $this;
    }

}
