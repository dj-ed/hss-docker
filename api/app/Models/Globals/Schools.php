<?php

namespace App\Models\Globals;

use App\Models\Base;

/**
 * @property integer $id
 * @property integer $state_id
 * @property integer $county_id
 * @property string $name
 * @property string $type
 * @property string $address
 * @property string $phone
 * @property integer $zip_code_id
 * @property States $state
 * @property Counties $county
 */
class Schools extends Base
{
    protected $connection = 'mysql_global';

    /**
     * @var array
     */
    protected $fillable = [
        'state_id',
        'county_id',
        'name',
        'type',
        'address',
        'phone',
        'zip_code_id'
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
    public function state()
    {
        return $this->belongsTo('App\Models\Globals\States');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function county()
    {
        return $this->belongsTo('App\Models\Globals\Counties');
    }
}
