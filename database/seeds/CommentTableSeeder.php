<?php

use App\Comment;
use App\Post;
use App\User;
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
            factory(Comment::class)->create([
                'user_id' => $users->random()->id,
                'post_id' => $posts->random()->id
            ]);
        }
    }
}
