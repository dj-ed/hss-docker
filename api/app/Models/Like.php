<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $model_id
 * @property string $model_type
 * @property integer $user_id
 */
class Like extends Base
{
    /**
     * @var string
     */
    protected $table = 'likes';

    /**
     * @var array
     */
    protected $fillable = ['model_id', 'model_type', 'user_id'];

    /**
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * @var string
     */
    protected $updated_at = '';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }
}
