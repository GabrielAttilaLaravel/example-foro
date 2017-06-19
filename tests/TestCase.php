<?php

use App\{Post, User, Comment};
use GrahamCampbell\Markdown\Facades\Markdown;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * @var \App\User
     */
    protected $defaultUser;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    // creamos un usuario y lo retornamos
    public function defaultUser(array $attributes = [])
    {
        // nos aseguramos si el user fue creado
        if ($this->defaultUser){
            return $this->defaultUser;
        }

        return $this->defaultUser = factory(User::class)->create($attributes);
    }

    protected function createPost(array $attributes = [])
    {
        return factory(Post::class)->create($attributes);
    }

    protected function createComment(array $attributes = [])
    {
        return factory(Comment::class)->create($attributes);
    }

    protected function support_markdown($obj, $url, $text){
        $obj->visit($url)
            ->seeInElement('strong', $text);
    }

    protected function xss_attack($obj, $url, $xssAttack){
        $obj->visit($url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal')
            ->seeText($xssAttack);
    }

    protected function xss_attack_with_html($obj, $url, $xssAttack){
        $obj->visit($url)
            ->dontSee($xssAttack);
    }
}
