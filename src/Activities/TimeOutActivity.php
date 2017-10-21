<?php
/**
 * Created by PhpStorm.
 * User: ProductMgr_170
 * Date: 10/21/2017
 * Time: 4:44 PM
 */

namespace Jowusu837\HubtelUssd\Activities;


use Jowusu837\HubtelUssd\Lib\UssdActivity;
use Jowusu837\HubtelUssd\Lib\UssdResponse;

class TimeOutActivity extends UssdActivity
{
    public function run()
    {
        $this->response->Type = UssdResponse::RELEASE;
        $this->response->Message = "Oops! Seems your session has timed out!";
        return $this;
    }
}