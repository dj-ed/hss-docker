<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $title
 * @property string $shop_url
 * @property School $schools
 * @property User $users
 */
class SchoolGeneral extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'school_general';

    /**
     * @var array
     */
    protected $fillable = ['title', 'shop_url'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

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
    public function users()
    {
        return $this->hasMany('App\Models\User', 'school_id');
    }

}
