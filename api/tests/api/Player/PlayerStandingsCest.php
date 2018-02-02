<?php

use Codeception\Util\HttpCode;

class PlayerStandingsCest
{
    /**
     * @var int
     */
    public $playerId = 1;

    /**
     * @var array
     */
    public $viewType = [
        'state',
        'school',
        'team'
    ];

    /**
     * @var array
     */
    public $jsonType = [
        'columns' => [
            'innerColumns' => 'array',
            'innerColumnsName' => 'array',
        ],
        'poweredBy' => 'boolean',
        'pages' => 'array'
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
        $I->sendGET('player/player-standings');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function playerStandingsByState(ApiTester $I)
    {
        $I->sendPOST('player/player-standings', [
            'playerId' => $this->playerId,
            'viewType' => $this->viewType[0]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }

    /**
     * @param ApiTester $I
     */
    public function playerStandingsBySchool(ApiTester $I)
    {
        $I->sendPOST('player/player-standings', [
            'playerId' => $this->playerId,
            'viewType' => $this->viewType[1]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }

    /**
     * @param ApiTester $I
     */
    public function playerStandingsByTeam(ApiTester $I)
    {
        $I->sendPOST('player/player-standings', [
            'playerId' => $this->playerId,
            'viewType' => $this->viewType[2]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
