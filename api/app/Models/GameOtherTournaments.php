<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $name
 */
class GameOtherTournaments extends Base
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

}
