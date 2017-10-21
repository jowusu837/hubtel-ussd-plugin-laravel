<?php

namespace Jowusu837\HubtelUssd;

use Illuminate\Support\Facades\Cache;
use Jowusu837\HubtelUssd\Activities\HomeActivity;
use Jowusu837\HubtelUssd\Lib\UssdActivity;
use Jowusu837\HubtelUssd\Lib\UssdRequest;
use Jowusu837\HubtelUssd\Lib\UssdResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Exception;

class MainController extends Controller
{
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
    protected $session;

    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->cache = Cache::store(env('USSD_SESSION_CACHE_DRIVER', 'file'));

        // Let's instantiate our next response
        $this->response = new UssdResponse();

        // Set request
        $this->request = (object)$request->json()->all();
        $this->sessionId = 'hubtel_ussd_session_' . $this->request->SessionId;

        // Check if cache is set
        $this->session = $this->retrieveSession();
    }

    /**
     * Main application entry point.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $activity = $this->initializeNextActivity()->run();

            // Session might have changed during activity:
            $this->updateSession($activity->getSession());

            // Get updated response from activity
            $this->response = $activity->getResponse();

            return $this->sendResponse();

        } catch (Exception $e) {

            // Let's log the error first
            \Log::error($e->getMessage(), $e->getTrace());

            // ... then we inform the user
            $this->response->Type = UssdResponse::RELEASE;
            $this->response->Message = "Oops! Something isn't right. Please try again later.";
            return $this->sendResponse();
        }
    }

    /**
     * Get where next the user must go
     * @return UssdActivity
     */
    protected function initializeNextActivity()
    {
        $previous_requested = $this->request->Message == env('USSD_BACK_CODE', '#');

        $next_activity_class = $previous_requested ? $this->session['previous_activity'] : $this->session['next_activity'];

        if (!$next_activity_class) {
            $next_activity_class = config('hubtel-ussd.home', HomeActivity::class);
        }

        /** @var UssdActivity $activity */
        $activity = new $next_activity_class($this->request, $this->response, $this->session);

        $this->updateSession([
            'next_activity' => $activity->next(),
            'previous_activity' => $next_activity_class
        ]);

        return $activity;
    }

    /**
     * Retrieve active user session from cache.
     *
     * @return mixed|null
     */
    protected function retrieveSession()
    {
        if ($this->cache->has($this->sessionId)) {
            return $this->cache->get($this->sessionId);
        }

        return null;
    }

    /**
     * Update current user session with $data
     *
     * @param array $data
     * @return void
     */
    protected function updateSession($data = [])
    {

        $oldSessionData = $this->retrieveSession();

        $updatedData = $oldSessionData ? array_merge($oldSessionData, $data) : $data;

        $expiresAt = Carbon::now()->addMinutes(env('USSD_SESSION_LIFETIME_IN_MINUTES', 5));

        $this->cache->put($this->sessionId, $updatedData, $expiresAt);

        $this->session = $updatedData;
    }

    /**
     * Send final response to user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function sendResponse()
    {
        return response()->json($this->response);
    }
}
