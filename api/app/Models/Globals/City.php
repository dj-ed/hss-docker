<?php

namespace App\Models\Globals;

use App\Models\Base;

/**
 * @property integer $id
 * @property integer $state_id
 * @property integer $county_id
 * @property string $name
 * @property States $state
 * @property Counties $county
 */
class City extends Base
{
    protected $connection = 'mysql_global';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     * @var array
     */
    protected $fillable = ['state_id', 'county_id', 'name'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function state()
    {
        return $this->hasMany('App\Models\Globals\States', 'id', 'state_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function county()
    {
        return $this->hasMany('App\Models\Globals\Counties', 'id', 'county_id');
    }

    public static function getLocationCity($cityId, $seasonId)
    {
        return static::selectRaw('cities.id, cities.name, cities.state_id')->where([
            'cities.id' => $cityId,
            'school.season_id' => $seasonId
        ])->join(env('DB_DATABASE') . '.school', 'school.city_id', 'cities.id')->groupBy('cities.id')->first();
    }

    public static function getLocationCities($seasonId)
    {
        return static::selectRaw('cities.id, cities.name, cities.state_id')->where(['school.season_id' => $seasonId])
                     ->join(env('DB_DATABASE') . '.school', 'school.city_id', 'cities.id')->groupBy('cities.id')->get();
    }
}
