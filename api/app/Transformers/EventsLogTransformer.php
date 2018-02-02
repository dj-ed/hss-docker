<?php

namespace App\Transformers;

use App\Models\Base;
use App\Models\Comments;
use App\Models\CommentVotes;
use App\Models\EventsLog;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class EventsLogTransformer extends TransformerAbstract
{
    public function transform(EventsLog $eventsLog)
    {
        if ($eventsLog->from_user_id != null) {
            $beforeDescription = $eventsLog->fromUser->first_name . ' ' . $eventsLog->fromUser->last_name;
            $eventId = $eventsLog->relation_model_id;
            $eventType = Base::EVENTS_LOG[$eventsLog->relation_model_type];
            $eventPhoto = $eventsLog->fromUser->getPhoto();
            $contentImage = $eventsLog->url_image;

            switch ($eventsLog->model_type) {
                case CommentVotes::class:
                    $comments = Comments::find($eventsLog->relation_model_id);

                    $popupId = $comments->model_id;
                    $popupType = Base::EVENTS_LOG[$comments->model_type];

                    break;
                default:
                    $popupId = $eventsLog->relation_model_id;
                    $popupType = Base::EVENTS_LOG[$eventsLog->relation_model_type];
            }
            if ($eventsLog->model_type == Comments::class) {
                $eventId = $eventsLog->model_id;
                $eventType = Base::EVENTS_LOG[$eventsLog->model_type];
            }
        } else {
            if ($eventsLog->gameWhere == 'away') {
                $where = ' @ ';
            } else {
                $where = ' vs ';
            }
            if ($eventsLog->opponentTeamName == null) {
                $opponent = $eventsLog->opponentName;
            } else {
                $opponent = $eventsLog->opponentTeamName;
            }
            $team = preg_replace("/ {2,}/", ' ', $eventsLog->teamName);
            $opponent = preg_replace("/ {2,}/", ' ', $opponent);

            $beforeDescription = trim($team) . $where . trim($opponent);
            $eventId = $eventsLog->relation_model_id;
            $eventType = Base::EVENTS_LOG[$eventsLog->relation_model_type];
            $eventPhoto = $eventsLog->url_image;
            $contentImage = $eventsLog->url_image;

            $popupId = $eventsLog->relation_model_id;
            $popupType = Base::EVENTS_LOG[$eventsLog->relation_model_type];
        }

        return [
            'elementId' => $eventId,
            'elementType' => $eventType,
            'id' => $eventsLog->id,
            'type' => Base::EVENTS_LOG[$eventsLog->model_type],
            'beforeDescription' => $beforeDescription,
            'description' => $eventsLog->description,
            'date' => Carbon::parse($eventsLog->created_at)->diffForHumans(),
            'image' => $contentImage,
            'logo' => $eventPhoto,
            'popupId' => $popupId,
            'popupType' => $popupType,
            'status' => $eventsLog->status
        ];
    }
}