<?php

use Codeception\Util\HttpCode;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * @var string
     */
    public $email = 'admin@gmail.com';

    /**
     * @var string
     */
    public $password = '123123123';

    /**
     * @var string
     */
    public $password_fail = 'pass_fail';

    /**
     * @var
     */
    public $token;

    /**
     * Method Auth user
     */
    public function amUser()
    {
        $this->sendPOST('auth/login', ['email' => $this->email, 'password' => $this->password]);
        $this->seeResponseCodeIs(HttpCode::OK); // 200
        $this->seeResponseIsJson();
        $this->seeResponseJsonMatchesJsonPath('$.token');
        $token = $this->grabDataFromResponseByJsonPath('$.token');
        $this->token = $token[0];

        $this->amBearerAuthenticated($this->token);
        $this->sendPOST('auth/get-user');
        $this->seeResponseCodeIs(HttpCode::OK); // 200
        $this->seeResponseJsonMatchesJsonPath('$.id');
        $this->seeResponseMatchesJsonType([
            'id' => 'integer',
            'firstName' => 'string',
            'lastName' => 'string',
            'photoUrl' => 'string',
            'units' => 'string',
            'role' => 'string|string:empty'
        ]);
    }
}
