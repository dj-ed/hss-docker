<?php

namespace App\Models\Globals;

use App\Models\Base;
use Storage;

/**
 * @property integer $id
 * @property string $name
 * @property string $abbr
 * @property Counties $counties
 * @property Schools $schools
 */
class States extends Base
{
    protected $connection = 'mysql_global';

    /**
     * @var array
     */
    protected $fillable = ['name', 'abbr'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function counties()
    {
        return $this->hasMany('App\Models\Globals\Counties');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schools()
    {
        return $this->hasMany('App\Models\Globals\Schools');
    }

    public static function getNameById($id)
    {
        $model = static::where(['id' => $id])->first();
        if (!empty($model)) {
            return $model->name;
        }
    }

    public static function getShortNameById($id)
    {
        $model = static::where(['id' => $id])->first();
        if (!empty($model)) {
            return $model->abbr;
        }
    }

    public static function getFlagLogo($name)
    {
        $fileName = str_replace(' ', '-', strtolower($name));

        return Storage::disk('s3')->url('uploads/states-flag/' . $fileName . '.svg');
    }

    public static function getLocationStates($request)
    {
        return static::select(['states.id', 'states.name', 'states.abbr'])
                     ->where(['school.season_id' => $request->get('seasonId')])
                     ->join(env('DB_DATABASE') . '.school', 'school.state_id', 'states.id')->groupBy('states.id')
                     ->get();
    }
}
