<?php

use Codeception\Util\HttpCode;

class RosterCest
{
    /**
     * @var int
     */
    public $teamId = 1;

    /**
     * @var int
     */
    public $sportId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'players' => [
            [
                'id' => 'integer',
                'name' => 'string|NULL',
                'userPhotoUrl' => 'string|NULL',
                'number' => 'string|NULL',
                'positions' => 'array',
                'height' => 'string|NULL',
                'height_in' => 'string|NULL',
                'weight' => 'string|NULL',
                'stats' => [
                    'innerColumns' => 'array',
                    'innerColumnsName' => 'array',
                    'data' => 'array'
                ]
            ]
        ],
        'coaches' => [
            [
                'id' => 'integer',
                'name' => 'string|NULL',
                'type' => 'string|NULL',
                'userPhotoUrl' => 'string|NULL'
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
        $I->sendGET('team/roster');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function roster(ApiTester $I)
    {
        $I->sendPOST('team/roster', ['teamId' => $this->teamId, 'sportId' => $this->sportId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
