<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $active
 * @property string $title
 * @property string $full_title
 * @property GameOtherTeams $gameOtherTeams
 * @property Team $teams
 */
class TeamType extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_type';

    /**
     * @var array
     */
    protected $fillable = ['active', 'title', 'full_title'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gameOtherTeams()
    {
        return $this->hasMany('App\Models\GameOtherTeams');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    /**
     * @param $sportId
     * @param $genderId
     * @param $varsity
     * @return int|mixed|null
     */
    public static function getTeamTypeId($varsity)
    {
        $model = static::select(['id'])->where('title', $varsity)->first();
        if (!empty($model)) {
            return $model->id;
        }

        return null;
    }

    public static function teamTypeByGender($request)
    {

        $genderId = (!empty($request['genderId'])) ? $request['genderId'] : null;
        $rez = [];
        if (!empty($genderId)) {
            $genderList = array_filter(TeamGender::teamGenderList(), function ($el) use ($genderId) {
                return $el['id'] == $genderId;
            });
        } else {
            $genderList = TeamGender::teamGenderList();
        }
        foreach ($genderList as $gender) {
            foreach (static::teamTypeList() as $varsity) {
                $rez[] = [
                    'gender' => ['id' => $gender['id'], 'name' => $gender['name'], 'imgLogo' => $gender['imgLogo']],
                    'teamType' => ['id' => $varsity['id'], 'name' => $varsity['name']]
                ];
            }
        }

        return $rez;
    }

    public static function teamTypeList()
    {
        return static::all()->map(function ($type) {
            return [
                'id' => $type->title,
                'name' => $type->full_title,
            ];
        })->toArray();
    }
}
