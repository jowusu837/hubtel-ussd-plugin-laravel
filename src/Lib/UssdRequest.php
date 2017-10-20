<?php

namespace Jowusu837\HubtelUssd\Lib;
/**
 * UssdRequest:
 * Describes a USSD request object
 *
 * @author Victor J. Owusu <jowusu837@gmail.com>
 */
class UssdRequest {
    
    /**
     * REQUEST TYPES:
     */
    
    /**
     * indicates the first message in a USSD Session
     */
    const INITIATION = 'Initiation';
    
    /**
     * indicates a follow up in an already existing USSD session.
     */
    const RESPONSE = 'Response';
    
    /**
     * indicates that the subscriber is ending the USSD session.
     */
    const RELEASE = 'Release';
    
    /**
     * indicates that the USSD session has timed out.
     */
    const TIMEOUT = 'Timeout';
    
    /**
     * indicates that the user data should not be passed onto Hubtel (Safaricom Only).
     */
    const HIJACKSESSION = 'HijackSession';
    
    /**
     * Represents the phone number of the mobile subscriber.
     * Required: Yes
     * 
     * @var string
     */
    public $Mobile;
    
    /**
     * UUID string representing a unique identifier for the current USSD Session.
     * Required: Yes
     * 
     * @var string
     */
    public $SessionId;
    
    /**
     * Represents the USSD Service code assigned by the network.
     * Required: Yes
     * 
     * @var string
     */
    public $ServiceCode;

    /**
     * Indicates the type of USSD Request.
     * Required: Yes
     * 
     * @var string 
     */
    public $Type;
    
    /**
     * Represents the actual text entered by the mobile subscriber. For initiation, this will represent the USSD string entered by the subscriber. For Response, this will be the message sent.
     * Required: Yes
     * 
     * @var string
     */
    public $Message;
    
    /**
     * Indicates the network operator the subscriber belongs to.
     * Required: Yes
     * @var string
     */
    public $Operator;
    
    /**
     * Indicates the position of the current message in the USSD session.
     * Required: Yes
     * 
     * @var int
     */
    public $Sequence;
    
    /**
     * Represents data that API client asked API service to send from the previous USSD request. This data is only sent in the current request and is then discarded.
     * Maximum of 100 characters.
     * Required: No
     * 
     * @var string
     */
    public $ClientState;
    
    /**
     * Any network specific data will be sent through this parameter. This allows Hubtel to route data that is only available on a particular network to be routed to your application.
     * 
     * Required: No
     * @var stdClass
     */
    public $MetaData;
    
}
