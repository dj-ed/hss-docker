<?php

use Codeception\Util\HttpCode;

class GameRecapCest
{
    /**
     * @var int
     */
    public $teamId = 2;

    /**
     * @var int
     */
    public $gameId = 1277;

    /**
     * @var array
     */
    public $jsonGame = [
        'id' => 'integer',
        'date' => 'string|NULL',
        'dateTime' => 'string|NULL',
        'gameType' => 'string|NULL',
        'where' => 'string|NULL',
        'win' => 'integer|NULL',
        'scoreTeam' => 'integer|NULL',
        'scoreOpponent' => 'integer|NULL',
        'sportId' => 'integer|NULL',
        'location' => 'string|NULL',
        'team' => 'array|NULL',
        'opponentTeam' => 'array|NULL',
    ];

    /**
     * @var array
     */
    public $jsonType = [];

    /**
     * GameRecapCest constructor.
     */
    public function __construct()
    {
        $this->jsonType = [
            'games' => [
                'currentGame' => $this->jsonGame,
                'nextGame' => 'array|NULL',
                'prevGame' => $this->jsonGame
            ],
            'gameMedia' => 'array|NULL'
        ];
    }


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
        $I->sendGET('team/game-recap');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function gameRecap(ApiTester $I)
    {
        $I->sendPOST('team/game-recap', ['teamId' => $this->teamId]);
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
    public function gameRecapByGameId(ApiTester $I)
    {
        $I->sendPOST('team/game-recap', ['teamId' => $this->teamId, 'gameId' => $this->gameId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
