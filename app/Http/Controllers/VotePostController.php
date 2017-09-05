<?php

namespace App\Http\Controllers;

use App\Post;
use App\Repositories\voteRepository;

class VotePostController extends Controller
{
    /**
     * @var VoteRepository
     */
    private $voteRepository;

    public function __construct(VoteRepository $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    //
    public function upvote(Post $post)
    {
        $this->voteRepository->upvote($post);

        return [
            'new_score' => $post->score
        ];
    }

    public function downvote(Post $post)
    {
        $this->voteRepository->downvote($post);

        return [
            'new_score' => $post->score
        ];
    }

    public function undoVote(Post $post)
    {
        $this->voteRepository->undoVote($post);

        return [
            'new_score' => $post->score
        ];
    }
}
