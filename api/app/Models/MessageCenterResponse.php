<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $from_uid
 * @property integer $message_id
 * @property integer $to_uid
 * @property string $type
 * @property integer $school_id
 * @property string $text
 * @property string $date
 * @property boolean $opened
 * @property User $user
 * @property MessageCenter $messageCenter
 */
class MessageCenterResponse extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'message_center_response';

    /**
     * @var array
     */
    protected $fillable = ['from_uid', 'message_id', 'to_uid', 'type', 'school_id', 'text', 'date', 'opened'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function messageCenter()
    {
        return $this->belongsTo('App\Models\MessageCenter', 'message_id');
    }

}
