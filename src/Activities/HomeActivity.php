<?php
/**
 * Created by PhpStorm.
 * User: ProductMgr_170
 * Date: 10/18/2017
 * Time: 9:51 PM
 */

namespace Jowusu837\HubtelUssd\Activities;

use App\Ussd\Lib\IUssdActivity;
use App\Ussd\Lib\UssdRequest;

class HomeActivity implements IUssdActivity
{
    /**
     * @param UssdRequest $request
     * @param array $session
     * @return string
     */
    public function run($request, $session) {
        return 'What is your name?';
    }

    public function next()
    {
        return SayMyNameActivity::class;
    }
}