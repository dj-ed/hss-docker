<?php

use Codeception\Util\HttpCode;

class CurrentVariablesCest
{
    /**
     * @var int
     */
    public $currentSportId = 1;

    /**
     * @var int
     */
    public $currentSeasonId = 1;

    /**
     * @var int
     */
    public $currentGenderId = 1;

    /**
     * @var int
     */
    public $currentVarsityId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'currentSeasonId' => 'integer|NULL',
        'currentSportId' => 'integer|NULL',
        'currentGenderId' => 'integer|NULL',
        'currentVarsityId' => 'integer|NULL',
        'seasonList' => 'array',
        'sportList' => 'array',
        'genderList' => 'array',
        'schoolList' => 'array',
        'varsityList' => 'array',
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
        $I->sendGET('root/current-variables');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function currentVariables(ApiTester $I)
    {
        $I->sendPOST('root/current-variables', [
            'currentSportId' => $this->currentSportId,
            'currentSeasonId' => $this->currentSeasonId,
            'currentGenderId' => $this->currentGenderId,
            'currentVarsityId' => $this->currentVarsityId,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType($this->jsonType);
    }
}
