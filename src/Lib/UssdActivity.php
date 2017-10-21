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
        // TODO: Implement next() method.
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
}