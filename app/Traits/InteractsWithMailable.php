<?php
namespace App\Traits;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\DomCrawler\Crawler;

trait InteractsWithMailable
{
    protected function open(Mailable $mailable)
    {
        // optenemos el transporte de email que estamos utilizando
        $transport = Mail::getSwiftMailer()->getTransport();

        // eliminamos los mensajes de la coleccion creando una nueva coleccion y reemplazando
        // la propiedad messages
        $transport->flush();

        Mail::send($mailable);

        // optenemos el primer mensaje
        $message = $transport->messages()->first();

        // cargamos un crawler con el contenido del mensaje del email
        // crawler: rastrean las páginas webs en busca de enlaces
        $this->crawler = new Crawler($message->getBody());

        return $this;
    }
}