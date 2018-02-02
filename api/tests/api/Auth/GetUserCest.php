<?php

use Codeception\Util\HttpCode;

class GetUserCest
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
        $I->sendGET('auth/get-user');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function getUser(ApiTester $I)
    {
        $I->sendPOST('auth/login', ['email' => $I->email, 'password' => $I->password]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
        $token = $I->grabDataFromResponseByJsonPath('$.token');

        $I->amBearerAuthenticated($token[0]);
        $I->sendPOST('auth/get-user');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseJsonMatchesJsonPath('$.id');
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'firstName' => 'string',
            'lastName' => 'string',
            'photoUrl' => 'string',
            'units' => 'string',
            'role' => 'string|string:empty'
        ]);
    }
}
