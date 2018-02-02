<?php

use Codeception\Util\HttpCode;

class PostViewsCest
{
    /**
     * @var int
     */
    public $modelId = 1;

    /**
     * @var array
     */
    public $modelType = [
        'news',
        'gallery'
    ];

    /**
     * @var string
     */
    public $badModelType = 'badModelType';

    /**
     * @var array
     */
    public $jsonType = [
        'success' => 'boolean'
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
        $I->sendGET('reviews/post-views');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function badModelType(ApiTester $I)
    {
        $I->sendPOST('reviews/post-views', ['modelId' => $this->modelId, 'modelType' => $this->badModelType]);
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR); // 500
    }

    /**
     * @param ApiTester $I
     */
    public function postViewsNews(ApiTester $I)
    {
        $I->sendPOST('reviews/post-views', ['modelId' => $this->modelId, 'modelType' => $this->modelType[0]]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType($this->jsonType);
    }

    /**
     * @param ApiTester $I
     */
    public function postViewsGallery(ApiTester $I)
    {
        $I->sendPOST('reviews/post-views', ['modelId' => $this->modelId, 'modelType' => $this->modelType[1]]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType($this->jsonType);
    }
}
