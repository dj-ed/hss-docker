<?php

namespace App\Http\Controllers\Api;

use App\Events\LogComments;
use App\Events\LogLikes;
use App\Models\Base;
use App\Models\CommentAbuse;
use App\Models\Comments;
use App\Models\CommentVotes;
use App\Transformers\CommentTransformer;
use Dingo\Api\Http\Request;
use \Storage;

/**
 * Class CommentController
 *
 * @package App\Http\Controllers\Api
 */
class CommentController extends ApiController
{

    /**
     * Get comments list in post
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/comment/get-comments",
     *     description="Get comments list in post",
     *     operationId="api.comment.get-comments",
     *     produces={"application/json"},
     *     tags={"Comments"},
     *     @SWG\Parameter(
     *     name="modelId",
     *     in="query",
     *     description="ID елемента секції",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="modelType",
     *     in="query",
     *     description="gallery, news, ...",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getComments(Request $request)
    {
        $modelType = Base::TYPES[$request->get('modelType')];
        $comments = Comments::where([
            'model_id' => $request->get('modelId'),
            'model_type' => $modelType,
            'reply_id' => null,
            'status' => Base::STATUS_ACTIVE
        ])->get();

        return $this->response->collection($comments, new CommentTransformer);
    }

    /**
     * Add message from comment
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/comment/post-text",
     *     description="Add message from comment",
     *     operationId="api.comment.post-text",
     *     produces={"application/json"},
     *     tags={"Comments Auth User"},
     *     @SWG\Parameter(
     *     name="modelId",
     *     in="query",
     *     description="ID елемента секції",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="modelType",
     *     in="query",
     *     description="gallery, news, ...",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="text",
     *     in="query",
     *     description="text comments",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="reply",
     *     in="query",
     *     description="ID of comment to reply or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function postTextComment(Request $request)
    {
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request->get('modelType')];
        $comment = Comments::create([
            'model_id' => $request->get('modelId'),
            'model_type' => $modelType,
            'text' => $request->get('text'),
            'user_id' => $user->id,
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'status' => Base::STATUS_ACTIVE,
            'reply_id' => $request->get('reply'),
            'app_name' => env('APP_NAME')
        ]);

        event(new LogComments($request->get('modelId'), $modelType, $comment, $user, Comments::class));

        return $this->response->item($comment, new CommentTransformer);
    }

    /**
     * Add audio file from comment
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/comment/post-audio",
     *     description="Add audio file from comment",
     *     operationId="api.comment.post-audio",
     *     produces={"application/json"},
     *     tags={"Comments Auth User"},
     *     consumes={"multipart/form-data"},
     *     @SWG\Parameter(
     *     name="modelId",
     *     in="query",
     *     description="ID елемента секції",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="modelType",
     *     in="query",
     *     description="gallery, news, ...",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="file",
     *     in="query",
     *     description="blob file data",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="reply",
     *     in="query",
     *     description="ID of comment to reply or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function postAudioComment(Request $request)
    {
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request->get('modelType')];
        $comment = Comments::create([
            'model_id' => $request->get('modelId'),
            'model_type' => $modelType,
            'is_audio' => 1,
            'user_id' => $user->id,
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'status' => Base::STATUS_ACTIVE,
            'reply_id' => $request->get('reply'),
            'app_name' => env('APP_NAME')
        ]);

        $resultFile = public_path('upload/tmp/' . $comment->id . '.mp3');
        exec("ffmpeg -i $request->file -vn -ar 22050 -ac 1 -ab 96 -f mp3 $resultFile", $output, $returnStatus);
        if ($returnStatus === 0) {
        } else {
            exec("/usr/local/Cellar/ffmpeg/3.4/bin/ffmpeg -i $request->file -vn -ar 22050 -ac 1 -ab 96 -f mp3 $resultFile");
        }

        Storage::disk('s3')->put('uploads/comments/' . $comment->id . '.mp3', file_get_contents($resultFile), 'public');
        unlink($resultFile);

        event(new LogComments($request->get('modelId'), $modelType, $comment, $user, Comments::class));

        return $this->response->item($comment, new CommentTransformer);
    }

    /**
     * Add like from comment
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/comment/add-like",
     *     description="Add like from comment",
     *     operationId="api.comment.add-like",
     *     produces={"application/json"},
     *     tags={"Comments"},
     *     @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     description="comment ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function addLike(Request $request)
    {
        $user = $this->auth->user();
        $commentData = [
            'comment_id' => $request->get('id'),
            'user_id' => $user->id,
            'app_name' => env('APP_NAME')
        ];
        $exists = CommentVotes::where($commentData)->count();
        if (empty($exists)) {
            $result = CommentVotes::create($commentData);

            event(new LogLikes($request->get('id'), Comments::class, $result, $user, CommentVotes::class));

            return $this->response->array(['success' => $result->id]);
        }
    }

    /**
     * Remove like from comment
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/comment/remove-like",
     *     description="Remove like from comment",
     *     operationId="api.comment.remove-like",
     *     produces={"application/json"},
     *     tags={"Comments"},
     *     @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     description="comment ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function removeLike(Request $request)
    {
        $user = $this->auth->user();
        $commentData = [
            'comment_id' => $request->get('id'),
            'user_id' => $user->id,
            'app_name' => env('APP_NAME')
        ];
        $deleted = CommentVotes::where($commentData)->delete();
        if (!empty($deleted)) {
            return $this->response->array(['success' => $deleted]);
        }
    }

    /**
     * Report abuse from comment
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/comment/report-abuse",
     *     description="Report abuse from comment",
     *     operationId="api.comment.report-abuse",
     *     produces={"application/json"},
     *     tags={"Comments"},
     *     @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     description="comment ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="reportType",
     *     in="query",
     *     description="string with type",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function reportAbuse(Request $request)
    {
        $created = CommentAbuse::create([
            'comment_id' => $request->get('id'),
            'report_type' => $request->get('reportType'),
        ]);

        return $this->response->array(['success' => $created]);
    }

}