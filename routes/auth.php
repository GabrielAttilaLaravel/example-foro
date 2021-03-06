<?php

// Routes that require authentication.

// Posts
Route::get('posts/create', [
    'uses' => 'CreatePostController@create',
    'as' => 'posts.create'
]);
Route::post('posts/create', [
    'uses' => 'CreatePostController@store',
    'as' => 'posts.store'
]);

// Votes
Route::post('posts/{post}-{slug}/upvote', [
    'uses' => 'VotePostController@upvote'
])->where('posts', '\d+');

Route::post('posts/{post}-{slug}/downvote', [
    'uses' => 'VotePostController@downvote'
])->where('posts', '\d+');

Route::delete('posts/{post}-{slug}/vote', [
    'uses' => 'VotePostController@undoVote'
])->where('posts', '\d+');

// Comments
Route::post('posts/{post}/comment', [
    'uses' => 'CommentController@store',
    'as' => 'comments.store'
]);

Route::post('comments/{comment}/accept', [
    'uses' => 'CommentController@accept',
    'as' => 'comments.accept'
]);

// Subscriptions
Route::post('posts/{post}/subscribe', [
    'uses' => 'SubscriptionController@subscribe',
    'as' => 'posts.subscribe'
]);

Route::delete('posts/{post}/subscribe', [
    'uses' => 'SubscriptionController@unsubscribe',
    'as' => 'posts.unsubscribe'
]);

Route::get('mis-posts/{category?}', [
    'uses' => 'ListPostController',
    'as' => 'posts.mine',
]);