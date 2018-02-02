<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;

/**
 * Class ApiController
 *
 * @package App\Http\Controllers\Api
 *
 * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http","https"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="HS-SPORT API"
 *     )
 * )
 */
class ApiController extends Controller
{
    use Helpers;
}