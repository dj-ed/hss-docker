<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $section_type
 * @property integer $article
 * @property integer $recap
 * @property integer $active
 * @property integer $order
 * @property integer $school_general_id
 */
class SchoolsStream extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schools_stream';

    /**
     * @var array
     */
    protected $fillable = ['section_type', 'article', 'recap', 'active', 'order', 'school_general_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
