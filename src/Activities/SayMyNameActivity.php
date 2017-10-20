<?php
/**
 * Created by PhpStorm.
 * User: ProductMgr_170
 * Date: 10/19/2017
 * Time: 10:02 PM
 */

namespace Jowusu837\HubtelUssd\Activities;


use App\Ussd\Lib\IUssdActivity;
use App\Ussd\Lib\UssdRequest;

class SayMyNameActivity implements IUssdActivity
{

    /**
     * This is the main entry point for this action
     * @param UssdRequest $request
     * @param array $session
     * @return mixed
     */
    public function run($request, $session)
    {
        return "Hi " . $request->Message;
    }

    /**
     * The next action to be executed
     * @return string
     */
    public function next()
    {
        return SayHowAreYouActivity::class;
    }
}