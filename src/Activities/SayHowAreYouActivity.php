<?php
/**
 * Created by PhpStorm.
 * User: ProductMgr_170
 * Date: 10/20/2017
 * Time: 8:47 AM
 */

namespace Jowusu837\HubtelUssd\Activities;


use App\Ussd\Lib\IUssdActivity;
use App\Ussd\Lib\UssdRequest;

class SayHowAreYouActivity implements IUssdActivity
{

    /**
     * This is the main entry point for this action
     * @param UssdRequest $request
     * @param array $session
     * @return mixed
     */
    public function run($request, $session)
    {
        return 'How are you?';
    }

    /**
     * The next action to be executed
     * @return mixed
     */
    public function next()
    {
        return null;
    }
}