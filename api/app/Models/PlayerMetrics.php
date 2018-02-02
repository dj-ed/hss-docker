<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $player_id
 * @property boolean $info_show
 * @property integer $height
 * @property float $height_in
 * @property float $weight
 * @property float $wingspan
 * @property float $total_reach
 * @property float $vertical_jump
 * @property integer $lane_speed
 * @property integer $shoe_size
 * @property string $hearing
 * @property string $eyesight
 * @property string $dominant_hand
 * @property float $no_step_vert
 * @property integer $mile_run_time
 * @property integer $lane_agility_speed
 * @property integer $bench_press_140lbs
 * @property float $gpa
 * @property integer $sat
 * @property integer $act
 * @property integer $transcripts
 * @property boolean $height_show
 * @property boolean $weight_show
 * @property boolean $wingspan_show
 * @property boolean $total_reach_show
 * @property boolean $vertical_jump_show
 * @property boolean $lane_speed_show
 * @property boolean $shoe_size_show
 * @property boolean $hearing_show
 * @property boolean $eyesight_show
 * @property boolean $no_step_vert_show
 * @property boolean $mile_run_time_show
 * @property boolean $lane_agility_speed_show
 * @property boolean $bench_press_140lbs_show
 * @property boolean $gpa_show
 * @property boolean $sat_show
 * @property boolean $act_show
 * @property boolean $transcripts_show
 * @property boolean $dominant_hand_show
 * @property float $squat_max
 * @property boolean $squat_max_show
 * @property float $bench_press_max
 * @property boolean $bench_press_max_show
 * @property float $lane_shuttle
 * @property boolean $lane_shuttle_show
 * @property float $court_sprint
 * @property boolean $court_sprint_show
 * @property Player $player
 */
class PlayerMetrics extends Base
{
    /**
     * @var array
     */
    protected $fillable = [
        'player_id',
        'info_show',
        'height',
        'height_in',
        'weight',
        'wingspan',
        'total_reach',
        'vertical_jump',
        'lane_speed',
        'shoe_size',
        'hearing',
        'eyesight',
        'dominant_hand',
        'no_step_vert',
        'mile_run_time',
        'lane_agility_speed',
        'bench_press_140lbs',
        'gpa',
        'sat',
        'act',
        'transcripts',
        'height_show',
        'weight_show',
        'wingspan_show',
        'total_reach_show',
        'vertical_jump_show',
        'lane_speed_show',
        'shoe_size_show',
        'hearing_show',
        'eyesight_show',
        'no_step_vert_show',
        'mile_run_time_show',
        'lane_agility_speed_show',
        'bench_press_140lbs_show',
        'gpa_show',
        'sat_show',
        'act_show',
        'transcripts_show',
        'dominant_hand_show',
        'squat_max',
        'squat_max_show',
        'bench_press_max',
        'bench_press_max_show',
        'lane_shuttle',
        'lane_shuttle_show',
        'court_sprint',
        'court_sprint_show'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    static $inchAttributes = [
        'height_in',
        'wingspan',
        'total_reach',
        'vertical_jump',
        'no_step_vert',
    ];

    /**
     * @var array
     */
    static $weightAttributes = [
        'weight',
        'squat_max',
        'bench_press_140lbs',
        'bench_press_max',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    public function convertToImperial()
    {
        foreach (static::$inchAttributes as $attribute) {
            if ($attribute == 'height_in') {
                $sm = floatval($this->height_in) + floatval($this->height) * 100;
                $inch = round($sm * 0.393701, 1);
                $this->height = floor($inch / 12);
                $this->height_in = round($inch - $this->height * 12, 4);
            } else {
                if (!empty($this->$attribute)) {
                    $this->$attribute = round($this->$attribute * 0.393701, 4);
                }
            }
        }
        foreach (static::$weightAttributes as $attribute) {
            if (!empty($this->$attribute)) {
                $this->$attribute = round($this->$attribute * 2.20462);
            }
        }
        $this->mile_run_time = round(floatval($this->mile_run_time) * 3.28084);
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public static function convertToMetric($attributes)
    {
        foreach (static::$inchAttributes as $attribute) {
            if (array_key_exists($attribute, $attributes)) {
                if ($attribute == 'height_in') {
                    $inch = $attributes['height_in'] + $attributes['height'] * 12;
                    $sm = round($inch * 2.54);
                    $attributes['height'] = floor($sm / 100);
                    $attributes['height_in'] = round($sm - $attributes['height'] * 100, 1);
                } else {
                    if (!empty($attribute)) {
                        $attributes[$attribute] = round($attributes[$attribute] * 2.54);
                    }
                }
            }
        }
        foreach (static::$weightAttributes as $attribute) {
            if (array_key_exists($attribute, $attributes)) {
                if (!empty($attributes[$attribute])) {
                    $attributes[$attribute] = round($attributes[$attribute] * 0.453592);
                }
            }
        }
        if (array_key_exists('mile_run_time', $attributes)) {
            $attributes['mile_run_time'] = round($attributes['mile_run_time'] * 0.3048);
        }

        return $attributes;
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public static function roundInches($attributes)
    {
        foreach (static::$inchAttributes as $attribute) {
            if (array_key_exists($attribute, $attributes)) {
                if (!empty($attribute)) {
                    if ($attribute == 'height_in') {
                        $attributes[$attribute] = round($attributes[$attribute], 1);
                    } else {
                        $attributes[$attribute] = round($attributes[$attribute]);
                    }
                }
            }
        }

        return $attributes;
    }

    /**
     * @param string $sizeSystem
     * @param $measurements
     * @return string
     */
    public static function getFormatDistanceMetric($sizeSystem = '', $measurements)
    {
        if (!empty($measurements)) {
            if ($sizeSystem == 'metric') {
                return (int) ($measurements / 100) . 'm ' . $measurements % 100 . 'cm';
            } else {
                return (int) ($measurements / 12) . '\'' . $measurements % 12 . '"';
            }
        }

        return '';
    }

    /**
     * @param string $sizeSystem
     * @param $measurements
     * @return string
     */
    public static function getFormatWeightMetric($sizeSystem = '', $measurements)
    {
        if (!empty($measurements)) {
            if ($sizeSystem == 'metric') {
                return $measurements . ' kg';
            } else {
                return $measurements . ' lbs';
            }
        }

        return '';
    }

    /**
     * @param string $sizeSystem
     * @param $ft
     * @param $inch
     * @return string
     */
    public static function getFormatHeightMetric($sizeSystem = '', $ft, $inch)
    {
        $str = '';
        if ($sizeSystem == 'metric') {
            if (!empty($ft)) {
                $str .= $ft . 'm';
            }
            if (!empty($inch)) {
                $str .= $inch . 'cm';
            }
        } else {
            if (!empty($ft)) {
                $str .= $ft . '\'';
            }
            if (!empty($inch)) {
                $str .= $inch . '"';
            }
        }

        return $str;
    }
}
