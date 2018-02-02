<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $title
 */
class TeamGender extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_gender';

    /**
     * @var array
     */
    protected $fillable = ['title'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function teamGenderList()
    {
        return static::all()->map(function ($gender) {
            return [
                'id' => $gender->id,
                'name' => $gender->title,
                'imgLogo' => ($gender->title == 'Boys') ? '/img/male.svg' : '/img/female.svg'
            ];
        })->toArray();
    }
}
