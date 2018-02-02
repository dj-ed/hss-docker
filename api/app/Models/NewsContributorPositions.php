<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $name
 * @property Contributors $contributors
 * @property NewsContributors $newsContributors
 */
class NewsContributorPositions extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contributors()
    {
        return $this->hasMany('App\Models\Contributors', 'position_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newsContributors()
    {
        return $this->hasMany('App\Models\NewsContributors', 'position_id');
    }
}
