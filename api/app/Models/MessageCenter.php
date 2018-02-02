<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $from_uid
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $access_block
 * @property string $access_role
 * @property string $date
 * @property string $model
 * @property integer $model_id
 * @property integer $school_id
 * @property string $action
 * @property integer $count
 * @property integer $user_confirm
 * @property string $status
 * @property string $update_time
 * @property boolean $opened
 * @property boolean $top_message
 * @property User $user
 * @property MessageCenterResponse $messageCenterResponses
 */
class MessageCenter extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'message_center';

    /**
     * @var array
     */
    protected $fillable = [
        'from_uid',
        'title',
        'description',
        'type',
        'access_block',
        'access_role',
        'date',
        'model',
        'model_id',
        'school_id',
        'action',
        'count',
        'user_confirm',
        'status',
        'update_time',
        'opened',
        'top_message'
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
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'from_uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messageCenterResponses()
    {
        return $this->hasMany('App\Models\MessageCenterResponse', 'message_id');
    }
}
