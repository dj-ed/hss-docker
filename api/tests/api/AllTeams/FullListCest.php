<?php

use Codeception\Util\HttpCode;

class FullListCest
{
    /**
     * @var int
     */
    public $sportId = 1;

    /**
     * @var int
     */
    public $seasonId = 9;

    /**
     * @var array
     */
    public $jsonType = [
        [
            'statesId' => 'integer',
            'stateName' => 'string|NULL',
            'stateShortName' => 'string|NULL',
            'stateLogo' => 'string|NULL',
            'count' => 'integer|NULL',
            'county' => [
                [
                    'countyId' => 'integer',
                    'countyName' => 'string|NULL',
                    'countyShortName' => 'string|NULL',
                    'count' => 'integer|NULL',
                    'sports' => [
                        [
                            'sportId' => 'integer',
                            'count' => 'integer|NULL',
                            'schools' => [
                                [
                                    'schoolId' => 'integer',
                                    'schoolName' => 'string|NULL',
                                    'principal' => 'string|NULL',
                                    'genders' => 'array',
                                    'leagues' => 'array',
                                    'count' => 'integer'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
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
        $I->sendGET('all-teams/full-list');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function fullList(ApiTester $I)
    {
        $I->sendPOST('all-teams/full-list', ['sportId' => $this->sportId, 'seasonId' => $this->seasonId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data[*]');
        }
    }
}
