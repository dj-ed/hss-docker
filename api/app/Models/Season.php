<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $title
 * @property integer $active
 * @property string $title_short
 * @property Obligation $obligations
 * @property School $schools
 * @property Team $teams
 * @property UserContent $userContents
 */
class Season extends Base
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'season';

    /**
     * @var array
     */
    protected $fillable = ['title', 'active', 'title_short'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function obligations()
    {
        return $this->hasMany('App\Models\Obligation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schools()
    {
        return $this->hasMany('App\Models\School');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userContents()
    {
        return $this->hasMany('App\Models\UserContent');
    }

    /*
     * Active Scope
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeActive($query)
    {
        return $query->where('active', Base::STATUS_ACTIVE);
    }

    public static function getLastActive()
    {
        return static::where('active', Base::STATUS_ACTIVE)->orderBy('id', 'DESC')->first();
    }
}
