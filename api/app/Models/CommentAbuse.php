<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $comment_id
 * @property integer $report_type
 * @property string $created_at
 * @property string $updated_at
 */
class CommentAbuse extends Base
{

    /**
     * @var array
     */
    protected $fillable = [
        'comment_id',
        'report_type',
    ];
}
