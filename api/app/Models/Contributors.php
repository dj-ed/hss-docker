<?php

namespace App\Models;


/**
 * @property integer $id
 * @property integer $position_id
 * @property string $email
 * @property string $name
 * @property NewsContributorPositions $newsContributorPosition
 * @property NewsContributors $newsContributors
 */
class Contributors extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['position_id', 'email', 'name'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function newsContributorPosition()
    {
        return $this->belongsTo('App\Models\NewsContributorPositions', 'position_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newsContributors()
    {
        return $this->hasMany('App\Models\NewsContributors');
    }
}
