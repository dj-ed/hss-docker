<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $comment_id
 * @property integer $user_id
 * @property string $app_name
 */
class CommentVotes extends Base
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'comment_id',
        'user_id',
        'app_name',
    ];
}
