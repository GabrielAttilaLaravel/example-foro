<?php


class SupportMarkdownCommentsTest extends FeatureTestCase
{
    function test_the_comment_content_support_markdown()
    {
        $importantText = 'Un comentario muy importante';

        $comment = $this->createComment([
            'comment' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->visit($comment->post->url)
            ->seeInElement('strong', $importantText);
    }

    function test_the_code_in_the_comment_is_escaped(){
        $xssAttack = "<script>alert('Sharing code!')</script>";

        $comment = $this->createComment([
            // verificamos el contenido en formato de markdown con `` para convertirlo en texto escapado
            // sino las quitamos y colocamos {!! Markdown::convertToHtml(e($post->content)) !!} "e()"
            'comment' => "$xssAttack. Texto normal."
        ]);

        $this->visit($comment->post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal')
            ->seeText($xssAttack);
    }

    // comprobamos que no estemos expuestos ataques xss (injection code javascript)
    function test_xss_attack(){
        $xssAttack = "<script>alert('Malicious 35!')</script>";

        $comment = $this->createComment([
            'comment' => "$xssAttack. Texto normal."
        ]);

        $this->visit($comment->post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal')
            ->seeText($xssAttack);
    }

    function test_xss_attack_with_html(){
        $xssAttack = "<img src='img.jpg'>";

        $comment = $this->createComment([
            'comment' => "$xssAttack. Text normal."
        ]);

        $this->visit($comment->post->url)
            ->dontSee($xssAttack);
    }
}
