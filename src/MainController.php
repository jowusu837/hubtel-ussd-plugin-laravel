<?php

namespace Jowusu837\HubtelUssd;

use App\Ussd\Activities\HomeActivity;
use App\Ussd\Lib\IUssdActivity;
use App\Ussd\Lib\UssdRequest;
use App\Ussd\Lib\UssdResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Cache;
use Exception;

class MainController extends Controller
{
    /**
     * @var UssdResponse
     */
    protected $response;

    /**
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
        $this->sessionId = 'ussd_session_' . $this->request->SessionId;

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
            $activity = $this->initializeNextActivity();
            return $this->_success($activity->run($this->request, $this->session));

        } catch (Exception $e) {
            \Log::error($e->getMessage(), $e);
            return $this->_error("Oops! Something isn't right. Please try again later.");
        }
    }

    protected function _formatResponse($type, $message)
    {
        $this->response->Type = $type;
        $this->response->Message = $message;
        return response()->json($this->response);
    }

    protected function _success($message)
    {
        return $this->_formatResponse(UssdRequest::RESPONSE, $message);
    }

    protected function _done($message)
    {
        return $this->_formatResponse(UssdResponse::RELEASE, $message);
    }

    protected function _error($message = 'Unknown error!')
    {
        return $this->_formatResponse(UssdResponse::RELEASE, $message);
    }

    /**
     * Get where next the user must go
     * @return IUssdActivity
     */
    protected function initializeNextActivity()
    {
        $previous_requested = $this->request->Message == env('USSD_BACK_CODE');

        $next_activity_class = $previous_requested ? $this->session['previous_activity'] : $this->session['next_activity'];

        if (!$next_activity_class) {
            $next_activity_class = HomeActivity::class;
        }

        $activity = new $next_activity_class;

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

        $expiresAt = Carbon::now()->addMinutes(env('USSD_SESSION_LIFETIME_IN_MINUTES'));

        $this->cache->put($this->sessionId, $updatedData, $expiresAt);

        $this->session = $updatedData;
    }
}
