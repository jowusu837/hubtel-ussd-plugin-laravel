<?php

namespace Jowusu837\HubtelUssd\Lib;

/**
 * Description of UssdResponse
 *
 * @author ProductMgr_170
 */
class UssdResponse {
    
    /**
     * RESPONSE TYPES:
     */
    /**
     * indicates that the application is ending the USSD session.
     */
    const RELEASE = 'Release';
    
    /**
     * indicates a response in an already existing USSD session.
     */
    const RESPONSE = 'Response';
    
    /**
     * Indicates the type of USSD Request.
     * @required
     * @var string
     */
    public $Type;

    /**
     * Represents the actual message on the mobile subscriber’s phone
     * 
     * @required
     * @var string
     */
    public $Message;
    
    /**
     * Represents data that API client wants API service to send in the next USSD request. This data is sent in the next USSD request only and is subsequently discarded. (Max of 100 characters)
     * @var string
     */
   public $ClientState;
    
    /**
     * It is used to indicate whether the current response in a USSD session from a mobile subscriber should be masked by Hubtel. This is a useful security feature for masking sensitive information such as financial transactions.
     * @var bool
     */
   public $Mask;
    
    /**
     * It indicates whether the next incoming request should be masked by Hubtel. It is also useful for masking sensitve information such as user PIN’s.
     * @var bool
     */
   public $MaskNextRoute;

   public function __construct()
   {
       $this->Type = self::RESPONSE;
   }

}
