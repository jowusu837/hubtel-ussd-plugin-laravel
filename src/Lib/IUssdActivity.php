<?php
/**
 * This serves as a blueprint for all ussd activities
 */

namespace Jowusu837\HubtelUssd\Lib;


interface IUssdActivity
{
    /**
     * This is the main entry point for this action
     * @param UssdRequest $request
     * @param array $session
     * @return mixed
     */
    public function run($request, $session);

    /**
     * The next action to be executed
     * @return mixed
     */
    public function next();
}