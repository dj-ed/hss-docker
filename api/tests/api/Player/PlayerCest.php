<?php

use Codeception\Util\HttpCode;

class PlayerCest
{
    /**
     * @var int
     */
    public $playerId = 1;

    /**
     * @var string
     */
    public $sizeSystem = 'metric';

    public $jsonType = [
        'id' => 'integer',
        'userPhotoUrl' => 'string',
        'logo' => 'string',
        'name' => 'string',
        'number' => 'string',
        'guard' => 'string',
        'guardShort' => 'string',
        'metrics' => 'string|array',
        'position' => 'string',
        'positionShort' => 'string',
        'teamId' => 'integer',
        'social' => 'string|array',
        'teamPlayers' => 'string|array'
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
        $I->sendGET('player');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function player(ApiTester $I)
    {
        $I->sendPOST('player', ['playerId' => $this->playerId]);
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
    public function playerBySizeSystemMetric(ApiTester $I)
    {
        $I->sendPOST('player', ['playerId' => $this->playerId, 'sizeSystem' => $this->sizeSystem]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
