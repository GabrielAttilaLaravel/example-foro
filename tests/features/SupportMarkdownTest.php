<?php


class SupportMarkdownTest extends FeatureTestCase
{
    function test_the_post_content_support_markdown()
    {
        $importantText = 'Un texto muy importante';

        $post = $this->createPost([
            'content' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->visit($post->url)
            ->seeInElement('strong', $importantText);
    }


    function test_the_code_in_the_post_is_escaped(){
        $xssAttack = "<script>alert('Sharing code!')</script>";

        $post = $this->createPost([
            // verificamos el contenido en formato de markdown con `` para convertirlo en texto escapado
            // sino las quitamos y colocamos {!! Markdown::convertToHtml(e($post->content)) !!} "e()"
            'content' => "$xssAttack. Texto normal."
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal')
            ->seeText($xssAttack);
    }

    // comprobamos que no estemos expuestos ataques xss (injection code javascript)
    function test_xss_attack(){
        $xssAttack = "<script>alert('Malicious 35!')</script>";

        $post = $this->createPost([
            'content' => "$xssAttack. Texto normal."
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal')
            ->seeText($xssAttack);
    }

    function test_xss_attack_with_html(){
        $xssAttack = "<img src='img.jpg'>";

        $post = $this->createPost([
            'content' => "$xssAttack. Text normal."
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack);
    }
}
