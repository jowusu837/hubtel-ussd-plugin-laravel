<?php
/**
 * Created by PhpStorm.
 * User: ProductMgr_170
 * Date: 10/21/2017
 * Time: 2:19 AM
 */

namespace Jowusu837\HubtelUssd\Lib;


class UssdActivity implements IUssdActivity
{
    /**
     * Current request
     * @var UssdRequest
     */
    protected $request;

    /**
     * Next response
     * @var UssdResponse
     */
    protected $response;

    /**
     * User session
     * @var mixed
     */
    protected $session;

    /**
     * Stores next activity
     * @var
     */
    protected $nextActivity;

    /**
     * UssdActivity constructor.
     *
     * @param UssdRequest $request
     * @param UssdResponse $response
     * @param mixed $session
     */
    public function __construct($request, $response, $session)
    {
        $this->request = $request;
        $this->response = $response;
        $this->session = $session;
    }

    public function run()
    {
        // TODO: Implement run() method.
    }

    public function next()
    {
        return $this->nextActivity;
    }

    /**
     * Get updated response from this activity.
     * @return UssdResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get update session from this activity
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }
    
    protected function setReleaseResponse($message) {
        $this->response->Type = \Jowusu837\HubtelUssd\Lib\UssdResponse::RELEASE;
        $this->response->Message = $message;
        return $this;
    }
    
    /**
     * Returns the subcode provided by the user.
     * E.g. A user dials *1234*1# and service code is *1234#, will return 1
     *
     * @return string
     * @throws \Exception
     */
    protected function getSubCode() {
        $code = config('hubtel-ussd.code', false);

        // check if code is set
        if(!$code){
            throw new \Exception('Please set your application code in hubtel-ussd config!');
        }

        $ussd_code_length = count(explode('*', $code));
        $request_message_length = count(explode('*', trim($this->request->Message)));

        // We always expect request message to be longer than ussd code if subcode is required:
        if (!($request_message_length > $ussd_code_length)) {
            return false;
        }

        return rtrim(array_last(explode('*', trim($this->request->Message))), '#');
    }
}