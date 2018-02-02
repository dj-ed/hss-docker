<?php

use Codeception\Util\HttpCode;

class GetCommentsCest
{
    /**
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     */
    public function _after(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     */
    public function badMethod(ApiTester $I)
    {
        $I->sendGET('comment/get-comments');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function badModelType(ApiTester $I)
    {
        $I->sendPOST('comment/get-comments', ['modelId' => 1, 'modelType' => 'badModelType']);
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR); // 500
    }

    /**
     * @param ApiTester $I
     */
    public function getCommentsNews(ApiTester $I)
    {
        $I->sendPOST('comment/get-comments', ['modelId' => 1, 'modelType' => 'news']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType([
                'id' => 'integer',
                'text' => 'string',
                'audioUrl' => 'boolean|string',
                'userName' => 'string',
                'userPhotoUrl' => 'string',
                'userId' => 'integer',
                'createdAt' => 'integer|string:date',
                'likes' => 'integer',
                'isVoted' => 'boolean|string',
                'replyId' => 'integer|NULL',
                'replies' => 'array',
            ], '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }

    /**
     * @param ApiTester $I
     */
    public function getCommentsMedia(ApiTester $I)
    {
        $I->sendPOST('comment/get-comments', ['modelId' => 97, 'modelType' => 'gallery']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType([
                'id' => 'integer',
                'text' => 'string',
                'audioUrl' => 'boolean|string',
                'userName' => 'string',
                'userPhotoUrl' => 'string',
                'userId' => 'integer',
                'createdAt' => 'integer|string:date',
                'likes' => 'integer',
                'isVoted' => 'boolean|string',
                'replyId' => 'integer|NULL',
                'replies' => 'array',
            ], '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }
}
