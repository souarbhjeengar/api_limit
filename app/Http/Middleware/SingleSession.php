<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SingleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $limit = config('api.rate_limit', 3);

        $key = $request->ip;
        $tries = RateLimiter::attempts($key);

        if ($tries >= $limit) {
            return response()->json([
                'message' => 'Too Many Requests',
                'errors' => [
                    'code' => '429',
                ],
            ], 429);
        }

        RateLimiter::hit($key);

        return $next($request);
    }
}
/*
Problem statement:- We are running a laravel application and receiving requests from worldwide. We want to rate limit API so only one session per IP address should access the application. Another session request should show a prompt for previous sessions in progress and does not allow new sessions.

Optional:- a button to continue the second session and close the previous session, the previous session on the browser window should be closed.

Create a public GitHub repository and push your code there 
Create a loom/zoom video of you explaining the task and your code as well as displaying your application to follow both of the above steps. 


Please complete the assessment to the best of your ability within the next 48 hours. If you have technical issues, please feel free to reach out.
*/