<?php

use Codeception\Util\HttpCode;

class FullListCest
{
    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'stateName' => 'string|NULL',
        'stateShortName' => 'string|NULL',
        'stateLogo' => 'string|NULL',
        'county' => 'array',
        'count_all_schools' => 'integer'
    ];

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
        $I->sendGET('all-schools/full-list');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function fullList(ApiTester $I)
    {
        $I->sendPOST('all-schools/full-list', ['seasonId' => $this->seasonId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->jsonType,'$.data[*]');
        }else{
            $I->seeResponseMatchesJsonType($this->jsonType,'$.data');
        }
    }
}
