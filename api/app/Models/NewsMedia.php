<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $news_id
 * @property string $title
 * @property string $source
 * @property string $size
 * @property string $location
 * @property string $type
 * @property boolean $is_cover
 * @property string $file_url
 * @property string $thumb_url
 * @property integer $sort_order
 * @property string $crop_data
 * @property News $news
 */
class NewsMedia extends Base
{
    /**
     * @var array
     */
    protected $fillable = [
        'news_id',
        'title',
        'source',
        'size',
        'location',
        'type',
        'is_cover',
        'file_url',
        'thumb_url',
        'sort_order',
        'crop_data'
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
