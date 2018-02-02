<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $model_id
 * @property string $model_type
 * @property integer $relation_model_id
 * @property integer $relation_model_type
 * @property integer $user_id
 * @property integer $from_user_id
 * @property string $description
 * @property string $url_image
 * @property integer $status
 * @property string $created_at
 * @property string $gameWhere
 * @property string $opponentTeamName
 * @property string $opponentName
 * @property string $teamName
 * @property User $fromUser
 */
class EventsLog extends Base
{
    const STATUS_READ = 2;
    const STATUS_NOT_READ = 1;

    /**
     * @var string
     */
    protected $table = 'events_logs';

    /**
     * @var array
     */
    protected $fillable = [
        'model_id',
        'model_type',
        'relation_model_id',
        'relation_model_type',
        'user_id',
        'from_user_id',
        'description',
        'url_image',
        'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo('App\Models\User', 'from_user_id');
    }

    public function scopeGame($query)
    {
        return $query->select([
            'events_logs.*',
            'game.date_time AS gameTime',
            'game.where AS gameWhere',
            'game.opponent_team_id AS opponentId',
            'game.opponent_team_name AS opponentName',
            'team.name AS teamName',
            'opponent.name as opponentTeamName'
        ])->leftJoin('game', function ($join) {
            $join->on('game.id', '=', 'relation_model_id')->where('events_logs.relation_model_type', '=', Game::class);
        })->leftJoin('team', function ($join) {
            $join->on('team.id', '=', 'game.team_id')->where('events_logs.relation_model_type', '=', Game::class);
        })->leftJoin('team AS opponent', function ($join) {
            $join->on('opponent.id', '=', 'game.opponent_team_id')
                 ->where('events_logs.relation_model_type', '=', Game::class);
        });
    }
}
