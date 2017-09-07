<?php
/**
 * Created by PhpStorm.
 * User: attila
 * Date: 06/09/17
 * Time: 10:56 PM
 */

namespace App\Traits\Post;
use App\Vote;

trait CanBeVoted
{
    public function upvote()
    {
        $this->addVote(1);
    }

    public function downvote()
    {
        $this->addVote(-1);
    }

    protected function addVote($amount)
    {
        // creamos o actualizamos el post
        // 1 - condicional para comprobar si existe o no el voto
        // 2 - valor hacer por el cual va hacer creado o acualizado
        Vote::updateOrCreate(
            ['post_id' => $this->id, 'user_id' => auth()->id()],
            ['vote' => $amount]
        );

        // Actualizamos el scrore del post
        $this->refreshPostScore();
    }

    public function undoVote()
    {
        // queremos que el voto coincida con el post y con el usuario que esta conectado para
        // poder eliminarlo
        Vote::where([
            'post_id' => $this->id,
            'user_id' => auth()->id()
        ])->delete();

        // Actualizamos el scrore del post
        $this->refreshPostScore();
    }


    /**
     * Actualizamos el scrore del post
     */
    protected function refreshPostScore()
    {
        // calcular el score del post sumando el total de votos de cada uno de los post
        $this->score = Vote::query()
            ->where(['post_id' => $this->id])
            ->sum('vote');

        $this->save();
    }
}