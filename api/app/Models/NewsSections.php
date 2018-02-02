<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $news_id
 * @property string $type_model
 * @property integer $type_id
 * @property string $app_name
 * @property News $news
 */
class NewsSections extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['news_id', 'type_model', 'type_id', 'app_name'];

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
