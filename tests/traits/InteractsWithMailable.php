<?php
namespace Tests\traits;

use Illuminate\Support\Facades\Mail;
use Symfony\Component\DomCrawler\Crawler;

trait InteractsWithMailable
{
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