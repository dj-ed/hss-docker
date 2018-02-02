<?php

// OLD NEWS COMMENTS. TODO: remove table on new design release.

namespace App\Models;

/**
 * @property integer $id
 * @property integer $news_id
 * @property integer $reply_id
 * @property string $text
 * @property string $author_name
 * @property integer $user_id
 * @property string $app_name
 * @property string $created_at
 * @property News $news
 * @property CommonComments $commonComments
 */
class CommonComments extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['news_id', 'reply_id', 'text', 'author_name', 'user_id', 'app_name', 'created_at'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function news()
    {
        return $this->belongsTo('App\Models\News');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commonComments()
    {
        return $this->belongsTo('App\Models\CommonComments', 'reply_id');
    }
}
