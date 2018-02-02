<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $news_id
 * @property integer $position
 * @property string $start_date
 * @property string $end_date
 * @property News $news
 */
class NewsLock extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news_lock';

    /**
     * @var array
     */
    protected $fillable = ['news_id', 'position', 'start_date', 'end_date'];

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
