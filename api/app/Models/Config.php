<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $name
 * @property string $value
 */
class Config extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'config';

    /**
     * @var array
     */
    protected $fillable = ['name', 'value'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

}
