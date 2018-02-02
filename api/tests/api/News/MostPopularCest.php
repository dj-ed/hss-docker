<?php

use Codeception\Util\HttpCode;

class MostPopularCest
{
    /**
     * @var int
     */
    public $sportId = 2;

    /**
     * @var int
     */
    public $genderId = 1;

    /**
     * @var int
     */
    public $isHeadline = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'title' => 'string',
        'date' => 'integer|string:date',
        'slug' => 'string',
        'likes' => 'integer',
        'comments' => 'integer',
        'thumbUrl' => 'string',
        'sport' => 'string',
        'gender' => 'string',
        'source' => 'string|NULL',
        'authorName' => 'string|NULL',
        'description' => 'string'
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
        $I->sendGET('news/most-popular');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function mostPopular(ApiTester $I)
    {
        $I->sendPOST('news/most-popular');
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

    /**
     * @param ApiTester $I
     */
    public function mostPopularBySport(ApiTester $I)
    {
        $I->sendPOST('news/most-popular', ['sportId' => $this->sportId]);
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

    /**
     * @param ApiTester $I
     */
    public function mostPopularByGender(ApiTester $I)
    {
        $I->sendPOST('news/most-popular', ['genderId' => $this->genderId]);
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

    /**
     * @param ApiTester $I
     */
    public function mostPopularByIsHeadlines(ApiTester $I)
    {
        $I->sendPOST('news/most-popular', ['isHeadline' => $this->isHeadline]);
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

    /**
     * @param ApiTester $I
     */
    public function mostPopularBySportAndGender(ApiTester $I)
    {
        $I->sendPOST('news/most-popular', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId
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

    /**
     * @param ApiTester $I
     */
    public function mostPopularBySportIsHeadlines(ApiTester $I)
    {
        $I->sendPOST('news/most-popular', [
            'sportId' => $this->sportId,
            'isHeadline' => $this->isHeadline
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

    /**
     * @param ApiTester $I
     */
    public function mostPopularByGenderIsHeadlines(ApiTester $I)
    {
        $I->sendPOST('news/most-popular', [
            'genderId' => $this->genderId,
            'isHeadline' => $this->isHeadline
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

    /**
     * @param ApiTester $I
     */
    public function mostPopularByAllFilters(ApiTester $I)
    {
        $I->sendPOST('news/most-popular', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'isHeadline' => $this->isHeadline
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
