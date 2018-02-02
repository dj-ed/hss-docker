<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $school_id
 * @property string $fb_link
 * @property boolean $fb_show
 * @property string $tw_link
 * @property boolean $tw_show
 * @property string $ins_link
 * @property boolean $ins_show
 * @property string $pin_link
 * @property boolean $pin_show
 * @property string $youtube_link
 * @property boolean $youtube_show
 * @property string $vimeo_link
 * @property boolean $vimeo_show
 * @property string $mpreps_link
 * @property boolean $mpreps_show
 * @property boolean $kross_show
 * @property School $school
 */
class SchoolSocials extends Base
{
    /**
     * @var array
     */
    protected $fillable = [
        'school_id',
        'fb_link',
        'fb_show',
        'tw_link',
        'tw_show',
        'ins_link',
        'ins_show',
        'pin_link',
        'pin_show',
        'youtube_link',
        'youtube_show',
        'vimeo_link',
        'vimeo_show',
        'mpreps_link',
        'mpreps_show',
        'kross_show'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School');
    }
}
