<?php

namespace App\Transformers;

use App\Models\Comments;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{

    public function transform(Comments $comment)
    {
        $replies = [];
        if ($comment->replies) {
            foreach ($comment->replies as $reply) {
                $replies[] = $this->innerTransform($reply);
            }
        }

        return $this->innerTransform($comment, $replies);
    }

    private function innerTransform(Comments $comment, $replies = [])
    {
        $isVoted = false;
        $countVotes = 0;
        $user = app('Dingo\Api\Auth\Auth')->user();

        if ($comment->votes) {
            $countVotes = $comment->votes->count();
            $users = $comment->votes->pluck('user_id')->toArray();
            if (!empty($user) && in_array($user->id, $users)) {
                $isVoted = true;
            }
        }

        return [
            'id' => $comment->id,
            'text' => $comment->text,
            'audioUrl' => $comment->audioUrl(),
            'userName' => $comment->user_name,
            'userPhotoUrl' => $comment->userPhoto(),
            'userId' => $comment->user_id,
            'createdAt' => strtotime($comment->created_at) * 1000,
            'likes' => $countVotes,
            'isVoted' => $isVoted,
            'replyId' => $comment->reply_id,
            'replies' => $replies,
        ];
    }
}