<?php

namespace Jowusu837\HubtelUssd;

use Illuminate\Support\Facades\Cache;
use Jowusu837\HubtelUssd\Activities\HijackSessionActivity;
use Jowusu837\HubtelUssd\Activities\HomeActivity;
use Jowusu837\HubtelUssd\Activities\ReleaseActivity;
use Jowusu837\HubtelUssd\Activities\TimeOutActivity;
use Jowusu837\HubtelUssd\Lib\UssdActivity;
use Jowusu837\HubtelUssd\Lib\UssdRequest;
use Jowusu837\HubtelUssd\Lib\UssdResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Exception;

class MainController extends Controller {

    /**
     * Next ussd response to be sent
     * @var UssdResponse
     */
    protected $response;

    /**
     * Current ussd request
     * @var UssdRequest
     */
    protected $request;

    /**
     * This is the key by which we save a user's session
     *
     * @var string
     */
    protected $sessionId;

    /**
     * Loaded cache driver
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * User session state
     * @var mixed|null
     */
    protected $session = [];

    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->cache = Cache::store(env('USSD_SESSION_CACHE_DRIVER', 'file'));

        // Let's instantiate our next response
        $this->response = new UssdResponse();

        // Set request
        $this->request = (object) $request->json()->all();
        $this->sessionId = 'hubtel_ussd_session_' . $this->request->SessionId;

        // Check if cache is set
        $this->session = $this->retrieveSession();
    }

    /**
     * Main application entry point.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        try {

            switch ($this->request->Type) {

                case UssdRequest::INITIATION:
                    $activity = $this->processInitiationRequest();
                    break;

                case UssdRequest::RELEASE:
                    $activity = $this->processReleaseRequest();
                    break;

                case UssdRequest::TIMEOUT:
                    $activity = $this->processTimeoutRequest();
                    break;

                case UssdRequest::RESPONSE:
                    $activity = $this->processResponseRequest();
                    break;

                case UssdRequest::HIJACKSESSION:
                    $activity = $this->processHijackSessionRequest();
                    break;

                default:
                    throw new Exception("Unknown request");
                    break;
            }

            // Session might have changed during activity:
            $this->updateSession(array_merge($activity->getSession(), ['activity' => get_class($activity)]));

            // Get updated response from activity
            $this->response = $activity->getResponse();

            return $this->sendResponse();
        } catch (Exception $e) {

            // Let's log the error first
            \Log::error($e->getMessage() . PHP_EOL . $e->getTraceAsString());

            // ... then we inform the user
            $this->response->Type = UssdResponse::RELEASE;
            $this->response->Message = env('APP_DEBUG', false) ? $e->getMessage() : "Oops! Something isn't right. Please try again later.";
            return $this->sendResponse();
        }
    }

    /**
     * Retrieve active user session from cache.
     *
     * @return mixed|null
     */
    protected function retrieveSession() {
        if ($this->cache->has($this->sessionId)) {
            return $this->cache->get($this->sessionId);
        }

        return [];
    }

    /**
     * Update current user session with $data
     *
     * @param array $data
     * @return void
     */
    protected function updateSession($data = []) {

        $oldSessionData = $this->retrieveSession();

        $updatedData = !empty($oldSessionData) ? array_merge($oldSessionData, $data) : $data;

        $expiresAt = Carbon::now()->addMinutes(env('USSD_SESSION_LIFETIME_IN_MINUTES', 5));

        $this->cache->put($this->sessionId, $updatedData, $expiresAt);

        $this->session = $updatedData;
        
        if(env('APP_DEBUG', false)){
            logger("---------- USSD session -------");
            logger($this->session);
        }
    }

    /**
     * Send final response to user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendResponse() {
        return response()->json($this->response);
    }

    /**
     * Initiation request
     * @return UssdActivity|void
     */
    private function processInitiationRequest() {
        $className = config('hubtel-ussd.home', HomeActivity::class);

        /** @var UssdActivity $activity */
        $activity = new $className($this->request, $this->response, $this->session);

        return $activity->run();
    }

    /**
     * Response request
     * @return type
     */
    private function processResponseRequest() {
        $className = $this->session['activity'];
        $activity = new $className($this->request, $this->response, $this->session);

        // Handle back action
        if (trim($this->request->Message) != env('USSD_BACK_CODE', '#')) {
            $className = $activity->next();
            $activity = new $className($this->request, $this->response, $activity->getSession());
        }

        return $activity->run();
    }

    private function processReleaseRequest() {
        $className = config('hubtel-ussd.release', ReleaseActivity::class);

        /** @var UssdActivity $activity */
        $activity = new $className($this->request, $this->response, $this->session);

        return $activity->run();
    }

    private function processTimeoutRequest() {
        $className = config('hubtel-ussd.timeout', TimeOutActivity::class);

        /** @var UssdActivity $activity */
        $activity = new $className($this->request, $this->response, $this->session);

        return $activity->run();
    }

    private function processHijackSessionRequest() {
        $className = config('hubtel-ussd.hijack_session', HijackSessionActivity::class);

        /** @var UssdActivity $activity */
        $activity = new $className($this->request, $this->response, $this->session);

        return $activity->run();
    }

}
