<?php

use Codeception\Util\HttpCode;

class RemoveLikeCest
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
        $I->sendGET('comment/remove-like');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function removeLike(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('comment/remove-like', ['id' => 33]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'integer']);
    }
}
