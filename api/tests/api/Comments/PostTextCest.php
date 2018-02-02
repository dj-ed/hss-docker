<?php

use Codeception\Util\HttpCode;

class PostTextCest
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
        $I->sendGET('comment/post-text');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function badModelType(ApiTester $I)
    {
        $I->sendPOST('comment/post-text', [
            'modelId' => 1,
            'modelType' => 'badModelType',
            'text' => 'testing comments',
            'reply' => null
        ]);
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR); // 500
    }

    /**
     * @param ApiTester $I
     */
    public function postCommentsTextNews(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('comment/post-text', [
            'modelId' => 1,
            'modelType' => 'news',
            'text' => 'testing comments',
            'reply' => null
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.id');
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
        ]);
    }

    /**
     * @param ApiTester $I
     */
    public function postCommentsTextNewsReply(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('comment/post-text', [
            'modelId' => 1,
            'modelType' => 'gallery',
            'text' => 'testing comments gallery',
            'reply' => 43
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.id');
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
        ]);
    }

    /**
     * @param ApiTester $I
     */
    public function postCommentsTextGallery(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('comment/post-text', [
            'modelId' => 97,
            'modelType' => 'gallery',
            'text' => 'testing comments gallery',
            'reply' => null
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.id');
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
        ]);
    }

    /**
     * @param ApiTester $I
     */
    public function postCommentsTextGalleryReply(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('comment/post-text', [
            'modelId' => 97,
            'modelType' => 'gallery',
            'text' => 'testing comments gallery',
            'reply' => 44
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.id');
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
        ]);
    }
}
