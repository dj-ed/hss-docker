<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $contributor_id
 * @property integer $position_id
 * @property integer $news_id
 * @property string $name
 * @property boolean $show_email
 * @property string $email
 * @property Contributors $contributor
 * @property NewsContributorPositions $newsContributorPosition
 * @property News $news
 */
class NewsContributors extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['contributor_id', 'position_id', 'news_id', 'name', 'show_email', 'email'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contributor()
    {
        return $this->belongsTo('App\Models\Contributors');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function newsContributorPosition()
    {
        return $this->belongsTo('App\Models\NewsContributorPositions', 'position_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function news()
    {
        return $this->belongsTo('App\Models\News');
    }
}
