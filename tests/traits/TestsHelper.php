<?php

namespace Tests\traits;

use App\{Post, User, Comment};

trait TestsHelper
{
    /**
     * @var \App\User
     */
    protected $defaultUser;

    // creamos un usuario y lo retornamos
    public function defaultUser(array $attributes = [])
    {
        // nos aseguramos si el user fue creado
        if ($this->defaultUser){
            return $this->defaultUser;
        }

        return $this->defaultUser = factory(User::class)->create($attributes);
    }

    // creamos un usuario y lo retornamos
    public function anyone()
    {
        return factory(User::class)->create();
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