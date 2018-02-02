<?php

use Codeception\Util\HttpCode;

class PlayerStandingsPageCest
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
     * @var int
     */
    public $page = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'stats' => 'array'
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
        $I->sendGET('player/player-standings-page');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function playerStandingsPageByState(ApiTester $I)
    {
        $I->sendPOST('player/player-standings-page', [
            'playerId' => $this->playerId,
            'viewType' => $this->viewType[0],
            'page' => $this->page
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
    public function playerStandingsPageBySchool(ApiTester $I)
    {
        $I->sendPOST('player/player-standings-page', [
            'playerId' => $this->playerId,
            'viewType' => $this->viewType[1],
            'page' => $this->page
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
    public function playerStandingsPageByTeam(ApiTester $I)
    {
        $I->sendPOST('player/player-standings-page', [
            'playerId' => $this->playerId,
            'viewType' => $this->viewType[2],
            'page' => $this->page
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
