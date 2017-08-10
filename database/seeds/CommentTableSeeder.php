<?php

use App\User;
use App\Post;
use App\Comment;
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    public function run()
    {

        // cargamos todos los usuarios del sistema
        $users = User::select('id')->get();

        // cargamos todos los posts del sistema
        $posts = Post::select('id')->get();

        for ($i = 0; $i < 250; $i++)
        {
            $comment = factory(Comment::class)->create([
                'user_id' => $users->random()->id,
                'post_id' => $posts->random()->id
            ]);

            if (rand(0, 1)){
                $comment->markAsAnswer();
            }
        }
    }
}
