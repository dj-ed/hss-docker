<?php

use Codeception\Util\HttpCode;

class SportScoreboardCest
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
    public $genderId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'games' => [
            [
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
            ]
        ],
        'gender' => 'array',
        'teamType' => 'array'
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
        $I->sendGET('statistics/sport-scoreboard');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function sportScoreBoard(ApiTester $I)
    {
        $I->sendPOST('statistics/sport-scoreboard', [
            'sportId' => $this->sportId,
            'seasonId' => $this->seasonId,
            'genderId' => $this->genderId,
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
