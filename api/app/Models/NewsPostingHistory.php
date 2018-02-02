<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $news_id
 * @property integer $user_id
 * @property string $app_name
 * @property string $date
 * @property string $description
 * @property string $user_name
 * @property string $extended_description
 * @property News $news
 */
class NewsPostingHistory extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news_posting_history';

    /**
     * @var array
     */
    protected $fillable = [
        'news_id',
        'user_id',
        'app_name',
        'date',
        'description',
        'user_name',
        'extended_description'
    ];

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
}
