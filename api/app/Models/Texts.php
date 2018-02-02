<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $text
 */
class Texts extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['url', 'title', 'text'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

}
