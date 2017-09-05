<?php
namespace App\Repositories;

use App\{Post, Vote};

class voteRepository
{
    public function upvote(Post $post)
    {
        $this->addVote($post , 1);
    }

    public function downvote(Post $post)
    {
        $this->addVote($post , -1);
    }

    protected function addVote(Post $post, $amount)
    {
        // creamos o actualizamos el post
        // 1 - condicional para comprobar si existe o no el voto
        // 2 - valor hacer por el cual va hacer creado o acualizado
        Vote::updateOrCreate(
            ['post_id' => $post->id, 'user_id' => auth()->id()],
            ['vote' => $amount]
        );

        // Actualizamos el scrore del post
        $this->refreshPostScore($post);
    }

    public function undoVote(Post $post)
    {
        // queremos que el voto coincida con el post y con el usuario que esta conectado para
        // poder eliminarlo
        Vote::where([
            'post_id' => $post->id,
            'user_id' => auth()->id()
        ])->delete();

        // Actualizamos el scrore del post
        $this->refreshPostScore($post);
    }

    /**
     * Actualizamos el scrore del post
     *
     * @param Post $post
     */
    protected function refreshPostScore(Post $post)
    {
        // calcular el score del post sumando el total de votos de cada uno de los post
        $post->score = Vote::where(['post_id' => $post->id])->sum('vote');

        $post->save();
    }
}