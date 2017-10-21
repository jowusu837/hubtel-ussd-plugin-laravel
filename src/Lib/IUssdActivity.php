<?php
/**
 * This serves as a blueprint for all ussd activities
 */

namespace Jowusu837\HubtelUssd\Lib;


interface IUssdActivity
{
    /**
     * Entry point for this activity.
     * @return UssdActivity
     */
    public function run();

    /**
     * The next action to be executed
     * @return mixed
     */
    public function next();
}