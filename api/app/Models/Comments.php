<?php

namespace App\Models;

use Cache;
use Carbon\Carbon;
use Config;
use Storage;

/**
 * @property integer $id
 * @property integer $model_id
 * @property string $model_type
 * @property string $text
 * @property boolean $is_audio
 * @property string $user_name
 * @property integer $user_id
 * @property integer $reply_id
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $app_name
 * @property CommentVotes $votes
 * @property Comments $replies
 */
class Comments extends Base
{
    static $userExistCache = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Comments', 'reply_id', 'id');
    }

    public function votes()
    {
        return $this->hasMany('App\Models\CommentVotes', 'comment_id', 'id');
    }

    /**
     * @var array
     */
    protected $fillable = [
        'model_id',
        'model_type',
        'text',
        'is_audio',
        'user_name',
        'user_id',
        'reply_id',
        'status',
        'app_name',
    ];

    public function userPhoto(){
        $cacheKey = 'comment-photo-'.$this->app_name.'-'.$this->user_id;
        $cacheTime = Carbon::now()->addHour(3);

        if (!Cache::has($cacheKey)) {
            Cache::remember($cacheKey, $cacheTime, function(){
                $userImgUrl = 'uploads/users/' . $this->user_id . '/thumb.jpg';
                Config::set('filesystems.disks.s3.bucket', $this->app_name);
                if (Storage::disk('s3')->exists($userImgUrl)) {
                    return Storage::disk('s3')->url($userImgUrl);
                }
                //default img
                return '/img/user.svg';
            });
        }

        return Cache::get($cacheKey);
    }

    public function audioUrl(){
        if ($this->is_audio) {
            $cacheKey = 'comment-audio-' . $this->app_name . '-' . $this->id;
            $cacheTime = Carbon::now()->addHour(12);

            if (!Cache::has($cacheKey)) {
                Cache::remember($cacheKey, $cacheTime, function () {
                    Config::set('filesystems.disks.s3.bucket', $this->app_name);
                    return Storage::disk('s3')->url('uploads/comments/' . $this->id . '.mp3');
                });
            }

            return Cache::get($cacheKey);
        }

        return false;
    }

}
