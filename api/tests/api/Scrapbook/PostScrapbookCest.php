<?php

use Codeception\Util\HttpCode;

class PostScrapbookCest
{
    public $modelId = 1;
    public $modelType = [
        'news',
        'gallery',
        'album',
        'badModelType'
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
        $I->sendGET('scrapbook/post-scrapbook');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function badModelType(ApiTester $I)
    {
        $I->sendPOST('scrapbook/post-scrapbook', ['modelId' => $this->modelId, 'modelType' => $this->modelType[3]]);
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR); // 500
    }

    /**
     * @param ApiTester $I
     */
    public function postScrapbookNews(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('scrapbook/post-scrapbook', ['modelId' => $this->modelId, 'modelType' => $this->modelType[0]]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'boolean']);
    }

    /**
     * @param ApiTester $I
     */
    public function postScrapbookGallery(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('scrapbook/post-scrapbook', ['modelId' => $this->modelId, 'modelType' => $this->modelType[1]]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'boolean']);
    }

    /**
     * @param ApiTester $I
     */
    public function postScrapbookAlbum(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('scrapbook/post-scrapbook', ['modelId' => $this->modelId, 'modelType' => $this->modelType[2]]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'boolean']);
    }
}
