<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $name
 * @property GameOtherTeams $gameOtherTeams
 */
class GameOtherSchools extends Base
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
    public function gameOtherTeams()
    {
        return $this->hasMany('App\Models\GameOtherTeams', 'school_id');
    }
}
