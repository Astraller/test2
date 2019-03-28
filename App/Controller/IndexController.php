<?php

namespace App\Controller;

use App\App;
use \App\System\BaseController;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends BaseController
{

    /**
     * @throws \App\System\Exceptions\UnauthorizedHttpException
     */
    public function postLogin()
    {
        $username = $this->requestData['username'];
        $password = $this->requestData['password'];

        $token = App::inst()->Authentication->authorize($username, $password);

        return [
            'token' => $token
        ];
    }

}