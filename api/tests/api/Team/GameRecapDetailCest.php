<?php

use Codeception\Util\HttpCode;

class GameRecapDetailCest
{
    /**
     * @var int
     */
    public $gameId = 1277;

    /**
     * @var array
     */
    public $jsonType = [
        'scoreboard' => [
            'columns' => 'array',
            'main' => 'array',
            'opponent' => 'array',
        ],
        'topGamePlayers' => [
            'columns' => 'array',
            'columnsName' => 'array',
            'main' => 'array',
            'opponent' => 'array'
        ],
        'topPlayers' => [
            'innerColumns' => 'array',
            'innerColumnsName' => 'array',
            'main' => 'array',
            'opponent' => 'array',
        ],
        'newsId' => 'array|NULL'
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
        $I->sendGET('team/game-recap-detail');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function gameRecapDetail(ApiTester $I)
    {
        $I->sendPOST('team/game-recap-detail', ['gameId' => $this->gameId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
