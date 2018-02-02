<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $model_id
 * @property string $model_type
 * @property integer $views
 */
class Review extends Base
{
    /**
     * @var string
     */
    protected $table = 'reviews';

    /**
     * @var array
     */
    protected $fillable = ['model_id', 'model_type', 'views'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @param $request
     * @param $modelType
     */
    public static function postReview($request, $modelType)
    {
        $review = static::where([
            'model_id' => $request->get('modelId'),
            'model_type' => $modelType,
        ])->first();

        if (empty($review)) {
            static::create([
                'model_id' => $request->get('modelId'),
                'model_type' => $modelType,
                'views' => 1
            ]);
        } else {
            $review->update(['views' => $review->views + 1]);
        }
    }
}
