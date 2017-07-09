<?php

namespace Tests;

use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Tests\traits\CreatesApplication;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();

        // creamos un macro para assertSeeErrors para dusk ya que los metodos de prueba
        // estan dentro de la clase Browser la cual es diferente a DuskTestCase
        Browser::macro('assertSeeErrors', function (array $fields)
        {
            foreach ($fields as $name => $errors){
                foreach ((array) $errors as $message){
                    $this->assertSeeIn(
                        "#field_{$name}.has-error .help-block", $message
                    );
                }
            }
        });
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        return RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()
        );
    }

    protected function configure($app)
    {
        $app->make('config')->set('database.default', 'mysql_dusk_local');
    }
}
