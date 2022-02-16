<?php

namespace FaarenTech\FaarenSDK;

use FaarenTech\FaarenSDK\Entities\ApiToken;
use FaarenTech\FaarenSDK\Exceptions\ApiTokenException;
use Illuminate\Support\Facades\Http;

class FaarenSDK
{
    protected ApiToken $apiToken;
    protected string $endpoint = "users/api-tokens/self";

    /**
     * Initialize the FAAREN SDK
     *
     * @param string $plainTextToken
     * @return FaarenSDK
     */
    public static function init(string $plainTextToken)
    {
        return new self($plainTextToken);
    }

    /**
     * @param string $plainTextToken
     * @throws ApiTokenException
     */
    public function __construct(string $plainTextToken)
    {
        $this->apiToken = $this->validateToken($plainTextToken);
    }

    /**
     * Validates the given token
     *
     * @param string $plainTextToken
     * @return ApiToken
     * @throws ApiTokenException
     */
    protected function validateToken(string $plainTextToken): ApiToken
    {
        // Necessary if already made by HandleApiTokenMiddleware
        if(request()->has('api_token') && request()->api_token instanceof ApiToken) {
            return request()->api_token;
        }

        $endpoint = config('faaren-sdk.service_url') . "/" . $this->endpoint;
        $response = Http::withToken($plainTextToken)->get($endpoint);

        if($response->failed()) {
            $reason = $response->object()->message ?? "No error message available";
            $status = $response->status();
            throw new ApiTokenException("ApiToken-Error: Http status {$status} - {$reason}");
        }

        return new ApiToken($response->object()->data, $plainTextToken);
    }

    /**
     * Returns the ApiToken-instance
     *
     * @return ApiToken
     */
    public function apiToken(): ApiToken
    {
        return $this->apiToken;
    }
}
