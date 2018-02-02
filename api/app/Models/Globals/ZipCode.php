<?php

namespace App\Models\Globals;

use App\Models\Base;

/**
 * @property integer $id
 * @property integer $zip
 * @property integer $city_id
 * @property City $city
 */
class ZipCode extends Base
{
    protected $connection = 'mysql_global';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zip_codes';

    /**
     * @var array
     */
    protected $fillable = ['city_id', 'zip'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function city()
    {
        return $this->hasOne('App\Models\Globals\City', 'id', 'city_id');
    }

    public static function getLocationByZip($zipCode)
    {
        return static::with('city')->whereRaw("zip_codes.zip LIKE '" . $zipCode . "%'")->get();
    }
}
