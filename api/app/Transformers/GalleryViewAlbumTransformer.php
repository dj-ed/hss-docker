<?php

namespace App\Transformers;

use App\Models\Player;
use App\Models\UserContent;
use League\Fractal\TransformerAbstract;

class GalleryViewAlbumTransformer extends TransformerAbstract
{
    public function transform($media)
    {
        return [
            'id' => $media->id,
            'title' => $media->title,
            'descriptions' => $media->description,
            'mediaType' => $media->media_type,
            'mediaUrl' => (!empty($media->source_link)) ? $media->source_link : UserContent::getMediaSourceLink($media->id, $media->media_type),
            'isIframe' => (!empty($media->source_link)) ? true : false,
            'likes' => $media->likes->count(),
            'comments' => $media->comments->count(),
            'album_id' => $media->album_id,
            'players' => (!empty($media->userContentPlayers->pluck('player_id'))) ? Player::getPlayersMedia($media->userContentPlayers->pluck('player_id')) : [],
        ];
    }
}