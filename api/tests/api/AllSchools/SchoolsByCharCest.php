<?php

use Codeception\Util\HttpCode;

class SchoolsByCharCest
{
    /**
     * @var int
     */
    public $seasonId = 9;

    /**
     * @var string
     */
    public $char = 'A';

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'name' => 'string|NULL',
        'logoUrl' => 'string|NULL',
        'state' => 'string|NULL',
        'stateShortName' => 'string|NULL',
        'county' => 'string|NULL',
        'sports' => 'array',
        'teamCount' => 'integer|NULL',
        'principal' => 'string|NULL',
        'socials' => 'array'
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
        $I->sendGET('all-schools/schools-by-char');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    public function schoolsByChar(ApiTester $I)
    {
        $I->sendPOST('all-schools/schools-by-char', ['seasonId' => $this->seasonId, 'char' => $this->char]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data[*]');
        } else {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data');
        }
    }
}
