<?php

use Codeception\Util\HttpCode;

class TopNewsCest
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
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'title' => 'string',
        'date' => 'integer|string:date',
        'source' => 'string|NULL',
        'text' => 'string',
        'tags' => 'array',
        'slug' => 'string',
        'likes' => 'integer',
        'comments' => 'integer',
        'sport' => 'string',
        'gender' => 'string',
        'media' => 'string|array',
        'contributors' => 'string|array'
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
        $I->sendGET('news/top-news');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function topNews(ApiTester $I)
    {
        $I->sendPOST('news/top-news');
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
    public function topNewsBySport(ApiTester $I)
    {
        $I->sendPOST('news/top-news', ['sportId' => $this->sportId]);
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
    public function topNewsByGender(ApiTester $I)
    {
        $I->sendPOST('news/top-news', ['genderId' => $this->genderId]);
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
    public function topNewsBySportAndGender(ApiTester $I)
    {
        $I->sendPOST('news/top-news', ['sportId' => $this->sportId, 'genderId' => $this->genderId]);
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
