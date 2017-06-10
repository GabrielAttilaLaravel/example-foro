<?php


class SupportMarkdownCommentsTest extends FeatureTestCase
{
    function test_the_comment_content_support_markdown()
    {
        $importantText = 'Un comentario muy importante';

        $comment = $this->createComment([
            'comment' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->support_markdown($this, $comment->post->url, $importantText);
    }

    // comprobamos que no estemos expuestos ataques xss (injection code javascript)
    function test_xss_attack(){
        $xssAttack = "<script>alert('Malicious 35!')</script>";

        // verificamos el contenido en formato de markdown con `` para convertirlo en texto escapado
        // sino las quitamos y colocamos {!! Markdown::convertToHtml(e($post->content)) !!} "e()"
        $comment = $this->createComment([
            'comment' => "$xssAttack. Texto normal."
        ]);

        $this->xss_attack($this, $comment->post->url, $xssAttack);
    }

    function test_xss_attack_with_html(){
        $xssAttack = "<img src='img.jpg'>";

        $comment = $this->createComment([
            'comment' => "$xssAttack. Text normal."
        ]);

        $this->xss_attack_with_html($this, $comment->post->url, $xssAttack);
    }
}
