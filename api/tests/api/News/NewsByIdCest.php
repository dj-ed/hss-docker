<?php

use Codeception\Util\HttpCode;

class NewsByIdCest
{
    /**
     * @var int
     */
    public $newsId = 1;
    public $notFound = 109009;

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
        $I->sendGET('news/news-by-id');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function newsNotFound(ApiTester $I)
    {
        $I->sendPOST('news/news-by-id', ['newsId' => $this->notFound]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND); // 200
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function newsById(ApiTester $I)
    {
        $I->sendPOST('news/news-by-id', ['newsId' => $this->newsId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType($this->jsonType);
    }
}
