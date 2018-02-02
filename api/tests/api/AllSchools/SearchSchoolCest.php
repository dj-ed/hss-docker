<?php

use Codeception\Util\HttpCode;

class SearchSchoolCest
{
    /**
     * @var int
     */
    public $seasonId = 9;

    /**
     * @var string
     */
    public $searchText = 'sch';

    /**
     * @var array
     */
    public $countyId = [335, 336];

    /**
     * @var int
     */
    public $stateId = 10;

    /**
     * @var array
     */
    public $jsonType = [
        'countyId' => 'integer|NULL',
        'stateId' => 'integer|NULL',
        'schools' => [
            [
                'id' => 'integer',
                'name' => 'string|NULL',
                'logoUrl' => 'string|NULL',
                'state' => 'string|NULL',
                'stateShortName' => 'string|NULL',
                'county' => 'string|NULL',
                'sports' => 'array',
                'teamCount' => 'integer|NULL',
                'principal' => 'string|NULL',
                'socials' => 'array'
            ]
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
        $I->sendGET('all-schools/search-school');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    public function searchSchoolMain(ApiTester $I)
    {
        $I->sendPOST('all-schools/search-school', ['seasonId' => $this->seasonId, 'searchText' => $this->searchText]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data[*]');
        } else {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data');
        }
    }

    public function searchSchoolByCounty(ApiTester $I)
    {
        $I->sendPOST('all-schools/search-school', [
            'seasonId' => $this->seasonId,
            'searchText' => $this->searchText,
            'countyId' => $this->countyId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data[*]');
        } else {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data');
        }
    }

    public function searchSchoolByState(ApiTester $I)
    {
        $I->sendPOST('all-schools/search-school', [
            'seasonId' => $this->seasonId,
            'searchText' => $this->searchText,
            'stateId' => $this->stateId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data[*]');
        } else {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data');
        }
    }
}
