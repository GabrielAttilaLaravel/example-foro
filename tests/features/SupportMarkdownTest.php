<?php


class SupportMarkdownTest extends FeatureTestCase
{
    function test_the_post_content_support_markdown()
    {
        $importantText = 'Un texto muy importante';

        $post = $this->createPost([
            'content' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->support_markdown($this, $post->url, $importantText);
    }

    // comprobamos que no estemos expuestos ataques xss (injection code javascript)
    function test_xss_attack(){
        $xssAttack = "<script>alert('Malicious 35!')</script>";

        // verificamos el contenido en formato de markdown con `` para convertirlo en texto escapado
        // sino las quitamos y colocamos {!! Markdown::convertToHtml(e($post->content)) !!} "e()"
        $post = $this->createPost([
            'content' => "$xssAttack. Texto normal."
        ]);
        $this->xss_attack($this, $post->url, $xssAttack);
    }

    function test_xss_attack_with_html(){
        $xssAttack = "<img src='img.jpg'>";

        $post = $this->createPost([
            'content' => "$xssAttack. Text normal."
        ]);

        $this->xss_attack_with_html($this, $post->url, $xssAttack);
    }
}
