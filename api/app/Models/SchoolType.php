<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $name
 */
class SchoolType extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'school_type';

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
