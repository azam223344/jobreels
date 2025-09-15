<?php

namespace App\Http\Middleware;

use Closure;
use \App\ApiLog;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        \Log::info([
            'link' => $request->fullUrl(),
            'headers' => $request->headers->__toString(),
            'request' => json_encode($request->request->all()),
            'response' => $response->content(),
        ]);
        //\Log::info('app.requests', ['request' => request()->all(), 'response' => $response]);
    }
}
