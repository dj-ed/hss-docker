<?php

namespace App\Models\Globals;

use App\Models\Base;

/**
 * @property integer $id
 * @property string $name
 * @property string $code
 */
class ColorNames extends Base
{
    protected $connection = 'mysql_global';

    /**
     * @var array
     */
    protected $fillable = ['name', 'code'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

}
