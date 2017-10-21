<?php
/**
 * Created by PhpStorm.
 * User: ProductMgr_170
 * Date: 10/21/2017
 * Time: 4:50 PM
 */

namespace Jowusu837\HubtelUssd\Activities;


use Jowusu837\HubtelUssd\Lib\UssdActivity;
use Jowusu837\HubtelUssd\Lib\UssdResponse;

class HijackSessionActivity extends UssdActivity
{
    public function run()
    {
        $this->response->Type == UssdResponse::RELEASE;
        $this->response->Message = "This is bad! Your session has been hijacked!";
        return $this;
    }
}