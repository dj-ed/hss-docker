<?php

use Codeception\Util\HttpCode;

class ScoreboardCest
{
    /**
     * @var int
     */
    public $sportId = 1;

    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var int
     */
    public $teamId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'date' => 'string|NULL',
        'dateTime' => 'string|NULL',
        'gameType' => 'string|NULL',
        'where' => 'string|NULL',
        'win' => 'integer|NULL',
        'team' => 'array',
        'opponentTeam' => 'array',
        'scoreTeam' => 'integer|NULL',
        'scoreOpponent' => 'integer|NULL',
        'sportId' => 'integer|NULL',
        'location' => 'string|NULL'
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
        $I->sendGET('statistics/scoreboard');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function scoreBoard(ApiTester $I)
    {
        $I->sendPOST('statistics/scoreboard', [
            'sportId' => $this->sportId,
            'seasonId' => $this->seasonId,
            'teamId' => $this->teamId,
            'page' => $this->page
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }
}
