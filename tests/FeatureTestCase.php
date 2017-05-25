<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class FeatureTestCase extends TestCase
{
    // usamos el trait DatabaseTransactions para que se eejcute dentro de una transaccion
    // y que la db siempre este vacia
    use DatabaseTransactions;
}