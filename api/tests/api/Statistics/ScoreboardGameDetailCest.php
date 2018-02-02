<?php

use Codeception\Util\HttpCode;

class ScoreboardGameDetailCest
{
    /**
     * @var int
     */
    public $gameId = 51;

    /**
     * @var array
     */
    public $jsonType = [
        'stats' => [
            [
                'game_id' => 'integer|NULL',
                'pts' => 'integer|NULL',
                'pf' => 'integer|NULL',
                'fg' => 'integer|NULL',
                'fgma_1' => 'integer|NULL',
                'fgma_2' => 'integer|NULL',
                'pma_1' => 'integer|NULL',
                'pma_2' => 'integer|NULL',
                'team_id' => 'integer|NULL',
                'first_name' => 'string|NULL',
                'last_name' => 'string|NULL',
                'number' => 'string|NULL',
                'id' => 'integer|NULL'
            ]
        ],
        'total' => 'array'
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
        $I->sendGET('statistics/scoreboard-game-detail');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function scoreBoardGameDetail(ApiTester $I)
    {
        $I->sendPOST('statistics/scoreboard-game-detail', [
            'gameId' => $this->gameId
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
