<?php

namespace App\Models;

/**
 * @property integer $id
 * @property string $title
 * @property News $news
 * @property NewsSectionTemplates $newsSectionTemplates
 * @property PlayerPositions $playerPositions
 */
class Sports extends Base
{
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany('App\Models\News');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newsSectionTemplates()
    {
        return $this->hasMany('App\Models\NewsSectionTemplates');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playerPositions()
    {
        return $this->hasMany('App\Models\PlayerPositions');
    }

    /**
     * @param $sportId
     * @return bool
     */
    public static function getSportGameStats($sportId)
    {
        $sport = static::where('id', $sportId)->first();
        if (!empty($sport)) {
            $statData['sportClass'] = 'App\Models\GamePlayerStat' . $sport->title;
            $statData['tableSport'] = 'game_player_stat_' . strtolower($sport->title);

            return $statData;
        }

        return false;
    }

    /**
     * @param $sportId
     * @return mixed|null|string
     */
    public static function getSportName($sportId)
    {
        $sport = static::find($sportId);

        return (!empty($sport)) ? $sport->title : null;
    }

    /**
     * @param $ids
     * @return \Illuminate\Support\Collection|static
     */
    public static function getSports($ids)
    {
        return static::whereIn('id', $ids)->get()->map(function ($sport) {
            return [
                'id' => $sport->id,
                'title' => $sport->title,
                'logoUrl' => '/img/sports/' . strtolower($sport->title) . '.svg'
            ];
        });
    }

    /**
     * @param $id
     * @return string
     */
    public static function getSportLogo($id)
    {
        $sport = static::find($id);

        return '/img/sports/' . strtolower($sport->title) . '.svg';
    }
}
