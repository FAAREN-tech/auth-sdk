<?php

namespace FaarenTech\FaarenSDK\Http\Middleware;

use FaarenTech\FaarenSDK\Entities\ApiToken;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Http;

class HandleApiTokenMiddleware
{
    protected string $endpoint = "users/api-tokens/self";

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

        $endpoint = config('faaren-sdk.service_url') . "/" . $this->endpoint;
        $response = Http::withToken($plainTextToken)->get($endpoint);

        if($response->failed()) {
            $reason = $response->object()->message ?? "No error message available";
            $status = $response->status();

            abort($status, "ApiToken-Error: {$reason}");
        }

        $token = new ApiToken($response->object()->data, $plainTextToken);

        $request->merge(['api_token' => $token]);

        return $next($request);
    }
}
