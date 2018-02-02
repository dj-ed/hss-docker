<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $sport_id
 * @property string $title
 * @property string $short_title
 * @property Sports $sport
 */
class PlayerPositions extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['sport_id', 'title', 'short_title'];

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
