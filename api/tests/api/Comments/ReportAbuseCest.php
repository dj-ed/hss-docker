<?php

use Codeception\Util\HttpCode;

class ReportAbuseCest
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
        $I->sendGET('comment/report-abuse');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function reportAbuse(ApiTester $I)
    {
        $I->sendPOST('comment/report-abuse', ['id' => 34, 'reportType' => 'testing report']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType([
            'comment_id' => 'integer|string',
            'report_type' => 'string',
            'updated_at' => 'string',
            'created_at' => 'string',
            'id' => 'integer',
        ], '$.success');
    }
}
