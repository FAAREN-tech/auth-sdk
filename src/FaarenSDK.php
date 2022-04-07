<?php

namespace FaarenTech\FaarenSDK;

use FaarenTech\FaarenSDK\Entities\AppToken;
use FaarenTech\FaarenSDK\Exceptions\AppTokenException;
use FaarenTech\FaarenSDK\NotificationService\NotificationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class FaarenSDK
{
    const TOKEN_SELF_URL = "app-tokens/self";
    protected AppToken $appToken;

    /**
     * Initialize the FAAREN SDK
     *
     * @param string $plainTextToken
     * @return FaarenSDK
     * @throws AppTokenException
     */
    public static function init(string $plainTextToken)
    {
        return new self($plainTextToken);
    }

    /**
     * @param string $plainTextToken
     * @throws AppTokenException
     */
    public function __construct(string $plainTextToken)
    {
        $this->appToken = $this->validateToken($plainTextToken);
    }

    /**
     * Validates the given token
     *
     * @param string $plainTextToken
     * @return AppToken
     * @throws AppTokenException
     */
    protected function validateToken(string $plainTextToken): AppToken
    {
        // Necessary if already made by HandleappTokenMiddleware
        if(request()->has('app_token') && request()->app_token instanceof AppToken) {
            return request()->app_token;
        }

        $endpoint = Str::replace(
            "//",
            "/",
            config('faaren-sdk.service_url') . "/" . self::TOKEN_SELF_URL
        );

        $response = Http::withToken($plainTextToken)->get($endpoint);

        if($response->failed()) {
            $reason = $response->object()->message ?? "No error message available";
            $status = $response->status();
            throw new AppTokenException("appToken-Error: Http status {$status} - {$reason}");
        }

        return new AppToken($response->object()->data, $plainTextToken);
    }

    /**
     * Returns the appToken-instance
     *
     * @return AppToken
     */
    public function appToken(): AppToken
    {
        return $this->appToken;
    }

    /**
     * @return NotificationService
     */
    #[Pure] public function notification(): NotificationService
    {
        return new NotificationService($this->appToken()->getPlainTextToken());
    }
}
