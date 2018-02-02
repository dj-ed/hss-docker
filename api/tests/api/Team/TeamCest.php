<?php

use Codeception\Util\HttpCode;

class TeamCest
{
    /**
     * @var int
     */
    public $teamId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'name' => 'string|NULL',
        'logoUrl' => 'string|NULL',
        'teamTypeName' => 'string|NULL',
        'mainColor' => 'string|NULL',
        'teamSocials' => 'array',
        'varsityId' => 'string|NULL',
        'albumId' => 'integer|NULL',
        'teams' => [
            'data' => [
                [
                    'id' => 'integer',
                    'name' => 'string|NULL',
                    'nameShort' => 'string|NULL',
                    'logoUrl' => 'string|NULL',
                    'type' => 'string|NULL',
                ]
            ]
        ],
        'school' => [
            'id' => 'integer',
            'name' => 'string|NULL',
            'shortName' => 'string|NULL',
            'logoUrl' => 'string|NULL',
            'stateName' => 'string|NULL',
            'city' => 'string|NULL',
            'address' => 'string|NULL',
            'zip' => 'string|NULL',
            'mainColor' => 'string|NULL',
            'secondColor' => 'string|NULL',
            'persons' => 'array',
            'phone' => 'string|NULL',
            'phoneExt' => 'string|NULL',
            'fax' => 'string|NULL',
            'faxExt' => 'string|NULL',
            'mascot' => 'string|NULL',
            'socials' => 'array'
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
        $I->sendGET('team');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function team(ApiTester $I)
    {
        $I->sendPOST('team', ['teamId' => $this->teamId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
