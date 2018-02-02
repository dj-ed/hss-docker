<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $player_id
 * @property string $athletes
 * @property string $movies
 * @property string $persons
 * @property string $words
 * @property string $sounds
 * @property string $meals
 * @property string $favorite_quote
 * @property string $bio
 * @property string $street
 * @property string $apl_suite
 * @property string $address_line2
 * @property string $city
 * @property integer $state_id
 * @property integer $zip
 * @property string $home_phone
 * @property string $home_ext
 * @property string $mobile
 * @property string $birthday_date
 * @property string $birthday_place
 * @property string $tmp_data
 * @property string $parents_names
 * @property string $siblings_names
 * @property string $hobby
 * @property string $book
 * @property string $holiday
 * @property string $video_game
 * @property string $beverage
 * @property string $thing_about_school
 * @property string $smart_phone
 * @property string $dream_vacation
 * @property string $influential_person
 * @property string $respect_players
 * @property string $career_ambitions
 * @property Player $player
 */
class PlayerInfo extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player_info';

    /**
     * @var array
     */
    protected $fillable = [
        'player_id',
        'athletes',
        'movies',
        'persons',
        'words',
        'sounds',
        'meals',
        'favorite_quote',
        'bio',
        'street',
        'apl_suite',
        'address_line2',
        'city',
        'state_id',
        'zip',
        'home_phone',
        'home_ext',
        'mobile',
        'birthday_date',
        'birthday_place',
        'tmp_data',
        'parents_names',
        'siblings_names',
        'hobby',
        'book',
        'holiday',
        'video_game',
        'beverage',
        'thing_about_school',
        'smart_phone',
        'dream_vacation',
        'influential_person',
        'respect_players',
        'career_ambitions'
    ];

    public static $offTheCourt = [
        'hobby' => 'FAVORITE HOBBY:',
        'book' => 'FAVORITE BOOK:',
        'holiday' => 'FAVORITE HOLIDAY:',
        'beverage' => 'FAVORITE BEVERAGE:',
        'athletes' => 'FAVORITE ATHLETES:',
        'words' => 'ONE WORD DESCRIBES ME:',
        'movies' => 'FAVORITE MOVIE:',
        'persons' => 'FAVORITE FAMOUS PERSON:',
        'sounds' => 'FAVORITE MUSIC:',
        'meals' => 'FAVORITE EAT:',
        'video_game' => 'FAVORITE VIDEO GAME:',
        'thing_about_school' => 'FAVORITE THING ABOUT SCHOOL:',
        'dream_vacation' => 'YOUR DREAM VACATION:',
        'influential_person' => 'MOST INFLUENTIAL PERSON ON YOUR CAREER:',
        'respect_players' => 'COLLEGE PLAYER YOU RESPECT MOST:',
        'career_ambitions' => 'WHAT ARE YOUR CAREER AMBITIONS:'
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
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * @param $dataExplode
     * @return array
     */
    public function getOffTheCourt($fields)
    {
        $result = ['data'=>[]];
        foreach ($fields as $key => $value) {
            $dataSting = explode('|', $this->$key);
            array_shift($dataSting);
            if (!empty($dataSting)) {
                $result['data'][] = $dataSting;
                $result['columnsName'][] = $value;
            }
        }

            return $result;
    }
}
