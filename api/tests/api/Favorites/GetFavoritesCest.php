<?php

use Codeception\Util\HttpCode;

class GetFavoritesCest
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
        $I->sendGET('favorites/get-favorite');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    public function getEvents(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('favorites/get-favorite');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.players');
        $I->seeResponseJsonMatchesJsonPath('$.coaches');
        $I->seeResponseJsonMatchesJsonPath('$.teams');
        $I->seeResponseJsonMatchesJsonPath('$.schools');
    }
}
