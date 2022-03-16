<?php

namespace FaarenTech\FaarenSDK\Http\Middleware;

use FaarenTech\FaarenSDK\Entities\AppToken;
use FaarenTech\FaarenSDK\FaarenSDK;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AcceptJsonMiddleware
{
    /**
     * Simply adds the Accept:application/json header to every incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set("Accept", "application/json");
        return $next($request);
    }
}
