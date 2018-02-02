<?php

use Codeception\Util\HttpCode;

class AuthCest
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
        $I->sendGET('auth/login');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function wrongCredentials(ApiTester $I)
    {
        $I->sendPOST('auth/login', ['email' => $I->email, 'password' => $I->password_fail]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseContainsJson([
            'message' => 'invalid_encode_token'
        ]);
    }

    /**
     * @param ApiTester $I
     */
    public function success(ApiTester $I)
    {
        $I->sendPOST('auth/login', ['email' => $I->email, 'password' => $I->password]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.token');
    }
}
