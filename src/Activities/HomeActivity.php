<?php
/**
 * Created by PhpStorm.
 * User: ProductMgr_170
 * Date: 10/18/2017
 * Time: 9:51 PM
 */

namespace Jowusu837\HubtelUssd\Activities;

use Jowusu837\HubtelUssd\Lib\UssdActivity;
use Jowusu837\HubtelUssd\Lib\UssdResponse;

class HomeActivity extends UssdActivity
{
    /**
     * @return string
     */
    public function run() {
        $this->response->Type = UssdResponse::RELEASE;
        $this->response->Message = 'Ussd is working!';
        return $this;
    }

}