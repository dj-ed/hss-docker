<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $title
 */
class UserType extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_type';

    /**
     * @var array
     */
    protected $fillable = ['title'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

}
