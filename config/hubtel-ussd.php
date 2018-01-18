<?php

return [
    /**
     * The ussd code for your application
     */
    "code" => env('APP_USSD_CODE', '*1234#'),
    
    /**
     * This is the entry point of your ussd application.
     */
    "home" => Jowusu837\HubtelUssd\Activities\HomeActivity::class,
    
    /**
     * Called on a release request
     */
    "release" => \Jowusu837\HubtelUssd\Activities\ReleaseActivity::class,
    
    /**
     * Called when session times out
     */
    "timeout" => \Jowusu837\HubtelUssd\Activities\TimeOutActivity::class,
    
    /**
     * Called when a hijack session event occurs
     */
    "hijack_session" => Jowusu837\HubtelUssd\Activities\HijackSessionActivity::class
];
