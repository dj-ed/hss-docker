<?php

namespace App\Models;

class Base extends \Eloquent
{
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    const TYPES = [
        'news' => News::class,
        'gallery' => UserContent::class,
        'game' => Game::class,
        'player' => Player::class,
        'coach' => TeamCoach::class,
        'team' => Team::class,
        'school' => School::class,
        'album' => UserContentAlbum::class
    ];

    const EVENTS_LOG = [
        Comments::class => 'comments',
        Event::class => 'events',
        CommentVotes::class => 'likes',
        Like::class => 'likes',
        News::class => 'news',
        UserContent::class => 'gallery',
        Game::class => 'game',
    ];

    /**
     * @param $str
     * @param array $noStrip
     * @return mixed|string
     */
    public static function camelCase($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
    }

    /**
     * fakeUser
     *
     * @param bool $type
     * @param $controller
     * @return object
     */
    public static function fakeUser($controller, $type = true)
    {
        if ($type) {
            $user = (object) collect([
                'id' => rand(1, 15),
                'first_name' => static::random_str('alphanum', 10),
                'last_name' => static::random_str('alphanum', 10)
            ])->all();
        } else {
            $user = $controller->auth->user();
        }

        return $user;
    }

    public static function random_str($type = 'alphanum', $length = 8)
    {
        switch ($type) {
            case 'basic'    :
                return mt_rand();
                break;
            case 'alpha'    :
            case 'alphanum' :
            case 'num'      :
            case 'nozero'   :
                $seedings = array();
                $seedings['alpha'] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['alphanum'] = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $seedings['num'] = '0123456789';
                $seedings['nozero'] = '123456789';

                $pool = $seedings[$type];

                $str = '';
                for ($i = 0; $i < $length; $i++) {
                    $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
                }
                return $str;
                break;
            case 'unique'   :
            case 'md5'      :
                return md5(uniqid(mt_rand()));
                break;
        }
        return 'test';
    }
}
