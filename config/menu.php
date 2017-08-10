<?php

return [
    // menu principal
    'items' => [
        'ver posts'          => ['route' => 'posts.index'],
        'crear posts'        => ['route' => 'posts.create']
    ],

    // menu lateral izquierdo
    'filters' => [
        'posts'              => ['route' => 'posts.index'],
        'posts pendientes'   => ['route' => 'posts.pending'],
        'posts completados'  => ['route' => 'posts.completed'],
    ]
];