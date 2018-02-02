<?php

use Codeception\Util\HttpCode;

class PostLikeCest
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
        $I->sendGET('likes/post-like');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function badModelType(ApiTester $I)
    {
        $I->sendPOST('likes/post-like', ['modelId' => 1, 'modelType' => 'badModelType']);
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR); // 500
    }

    /**
     * @param ApiTester $I
     */
    public function postLikeNews(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('likes/post-like', ['modelId' => 1, 'modelType' => 'news']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.likes');
        $I->seeResponseMatchesJsonType(['likes' => 'integer']);
    }

    /**
     * @param ApiTester $I
     */
    public function postLikeGallery(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('likes/post-like', ['modelId' => 1, 'modelType' => 'gallery']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.likes');
        $I->seeResponseMatchesJsonType(['likes' => 'integer']);
    }
}
