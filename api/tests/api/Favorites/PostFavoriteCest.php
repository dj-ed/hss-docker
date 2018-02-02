<?php

use Codeception\Util\HttpCode;

class PostFavoriteCest
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
        $I->sendGET('favorites/post-favorite');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function badModelType(ApiTester $I)
    {
        $I->sendPOST('favorites/post-favorite', ['modelId' => 2, 'modelType' => 'badModelType']);
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR); // 500
    }

    /**
     * @param ApiTester $I
     */
    public function postFavoritePlayer(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('favorites/post-favorite', ['modelId' => 3, 'modelType' => 'player']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'boolean|string']);
    }

    /**
     * @param ApiTester $I
     */
    public function postFavoriteCoach(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('favorites/post-favorite', ['modelId' => 3, 'modelType' => 'coach']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'boolean|string']);
    }

    /**
     * @param ApiTester $I
     */
    public function postFavoriteTeam(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('favorites/post-favorite', ['modelId' => 3, 'modelType' => 'team']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'boolean|string']);
    }

    /**
     * @param ApiTester $I
     */
    public function postFavoriteSchool(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('favorites/post-favorite', ['modelId' => 3, 'modelType' => 'school']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'boolean|string']);
    }
}
