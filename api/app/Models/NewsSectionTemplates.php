<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $sport_id
 * @property string $title
 * @property integer $gender
 * @property string $data
 * @property Sports $sport
 */
class NewsSectionTemplates extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['sport_id', 'title', 'gender', 'data'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Models\Sports');
    }
}
