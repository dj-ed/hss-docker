<?php

return [
    'host' => env('SPHINX_IP'),
    'port' => env('SPHINX_PORT'),

    'indexes' => [
        // users
        'users' => [
            'table' => 'user',
            'column' => 'id'
        ],
        // users
        'players' => [
            'table' => 'player',
            'column' => 'id'
        ],
        // users
        'coaches' => [
            'table' => 'team_coach',
            'column' => 'id'
        ],
        // teams
        'teams' => [
            'table' => 'team',
            'column' => 'id'
        ],
        // schools
        'schools' => [
            'table' => 'school',
            'column' => 'id'
        ],
        // news
        'news' => [
            'table' => 'news',
            'column' => 'id'
        ],
        // media
        'media' => [
            'table' => 'user_content',
            'column' => 'id'
        ],
        // media
        'album' => [
            'table' => 'user_content_albums',
            'column' => 'id'
        ],
        // media
        'photo' => [
            'table' => 'user_content',
            'column' => 'id'
        ],
        // media
        'video' => [
            'table' => 'user_content',
            'column' => 'id'
        ],
        // games
        'games' => [
            'table' => 'game',
            'column' => 'id'
        ],
        // player standings
        'player_standings' => [
            'table' => 'player',
            'column' => 'id'
        ],
    ]
];
