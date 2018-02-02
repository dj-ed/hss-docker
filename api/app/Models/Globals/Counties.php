<?php

namespace App\Models\Globals;

use App\Models\Base;

/**
 * @property integer $id
 * @property integer $state_id
 * @property string $name
 * @property States $state
 * @property Schools $schools
 */
class Counties extends Base
{
    protected $connection = 'mysql_global';

    /**
     * @var array
     */
    protected $fillable = ['state_id', 'name'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schools()
    {
        return $this->hasMany('App\Models\Globals\Schools');
    }

    /**
     * @param $name
     * @return string
     */
    public static function getShortName($name)
    {
        $nameArray = explode(' ', $name);
        if (count($nameArray) == 1) {
            $abbr = substr($nameArray[0], 0, 3);
        } elseif (count($nameArray) == 2) {
            $abbr = substr($nameArray[0], 0, 1) . substr($nameArray[1], 0, 2);
        } else {
            $abbr = substr($nameArray[0], 0, 1) . substr($nameArray[1], 0, 1) . substr($nameArray[2], 0, 1);
        }

        return strtoupper($abbr);
    }

    public static function getNameById($id)
    {
        $model = static::where(['id' => $id])->first();
        if (!empty($model)) {
            return $model->name;
        }
    }
}
