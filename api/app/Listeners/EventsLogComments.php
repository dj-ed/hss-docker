<?php

namespace App\Listeners;

use App\Events\LogComments;
use App\Models\Comments;
use App\Models\EventsLog;
use App\Models\News;
use App\Models\User;
use App\Models\UserContent;
use Carbon\Carbon;

class EventsLogComments
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LogComments $event
     * @return void
     */
    public function handle(LogComments $event)
    {
        $url_image = $description = $userId = null;

        switch ($event->modelType) {
            case News::class:
                $data = News::with('media')->find($event->modelId);
                $url = $data->media->filter(function ($value, $key) {
                    return $key == 'is_cover';
                })->pluck('thumb_url')->toArray();

                if ($event->object->reply_id == null) {
                    $url_image = (!empty($url)) ? $url[0] : null;
                    $userId = $data->author_id;
                    $description = ($event->object->is_audio == 1) ? 'added a voice comment for your article.' : 'added a comment for your article.';
                } else {
                    $userId = Comments::find($event->object->reply_id)->user_id;
                    $url_image = User::getPhotoById($event->user->id);
                    $description = ($event->object->is_audio == 1) ? 'added a voice reply comment for your comment.' : 'added a reply comment for your comment.';
                }
                break;
            case UserContent::class:
                $data = UserContent::with('userContentPlayers')->find($event->modelId);

                if ($event->object->reply_id == null) {
                    if (!empty($data->source_link)) {
                        $url_image = UserContent::getYoutubePhoto($data->source_link);
                    } else {
                        $url_image = UserContent::getMediaSourceLink($data->id, $data->media_type)['thumb'];
                    }
                    $userId = $data->author_id;
                    $description = ($event->object->is_audio == 1) ? 'added a voice comment for your media.' : 'added a comment for your media.';
                } else {
                    $userId = Comments::find($event->object->reply_id)->user_id;
                    $url_image = User::getPhotoById($event->user->id);
                    $description = ($event->object->is_audio == 1) ? 'added a voice reply comment for your media.' : 'added a reply comment for your media.';
                }

                if ($data->userContentPlayers->isNotEmpty()) {
                    foreach ($data->userContentPlayers->pluck('player_id') as $user_id) {
                        $arr = [
                            'model_id' => $event->object->id,
                            'model_type' => $event->relationModel,
                            'relation_model_id' => $event->modelId,
                            'relation_model_type' => $event->modelType,
                            'user_id' => $user_id,
                            'from_user_id' => $event->user->id,
                            'description' => $description,
                            'url_image' => $url_image,
                            'status' => EventsLog::STATUS_NOT_READ,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    EventsLog::insert($arr);
                }
                break;
        }

        if (!empty($data)) {
            EventsLog::create([
                'model_id' => $event->object->id,
                'model_type' => $event->relationModel,
                'relation_model_id' => $event->modelId,
                'relation_model_type' => $event->modelType,
                'user_id' => $userId,
                'from_user_id' => $event->user->id,
                'description' => $description,
                'url_image' => $url_image,
                'status' => EventsLog::STATUS_NOT_READ
            ]);
        }
    }
}
