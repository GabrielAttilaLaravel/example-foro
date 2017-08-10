<?php

use Tests\traits\{CreatesApplication, TestsHelper};
use Laravel\BrowserKitTesting\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeatureTestCase extends TestCase
{
    use CreatesApplication, DatabaseTransactions, TestsHelper;

    // usamos el trait DatabaseTransactions para que se eejcute dentro de una transaccion
    // y que la db siempre este vacia.
    use DatabaseTransactions;

    public function seeErrors(array $fields)
    {
        foreach ($fields as $name => $errors){
            foreach ((array) $errors as $message){
                $this->seeInElement(
                    "#field_{$name}.has-error .help-block", $message
                );
            }
        }
    }
}