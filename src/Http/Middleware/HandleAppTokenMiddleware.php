<?php

namespace FaarenTech\FaarenSDK\Http\Middleware;

use FaarenTech\FaarenSDK\Entities\AppToken;
use FaarenTech\FaarenSDK\FaarenSDK;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Http;

class HandleAppTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $plainTextToken = $request->bearerToken();

        $endpoint = config('faaren-sdk.service_url') . "/" . FaarenSDK::TOKEN_SELF_URL;
        $response = Http::withToken($plainTextToken)->get($endpoint);

        if($response->failed()) {
            $reason = $response->object()->message ?? "No error message available";
            $status = $response->status();

            abort($status, "appToken-Error: {$reason}");
        }

        $token = new AppToken($response->object()->data, $plainTextToken);

        $request->merge(['app_token' => $token]);

        return $next($request);
    }
}
