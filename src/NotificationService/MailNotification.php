<?php

namespace FaarenTech\FaarenSDK\NotificationService;

use FaarenTech\FaarenSDK\Exceptions\NotificationServiceException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MailNotification
{
    private const PRODUCTION_URL = "https://services.faaren.com/notification/";
    private const STAGING_URL = "https://services.staging.faaren.com/notification/";
    private const DEV_URL = "notification:8080/";

    private const PRODUCTION_TOKEN = "nq6FjyM8HkUYX59mCbuwFwnDgduw54pJMTRdY8MxjC5UkNhshRK5WTDv6kxdsnRX";
    private const STAGING_TOKEN = "zVHGJsQM4XL9CN8WqftvC6YWx2kgsbEnTeeXJrAUV77YgLFaXaWSwtV2W3Bn387y";
    private const LOCAL_TOKEN = "ZQMQqW9DaS4Gs9wpcSEQ5xjm7nYaQCb9K6dYwjTMFkrJwRww4C2BV28TCH26fMCk";

    /**
     * The mailing that should be sent
     * @var string|null
     */
    protected ?string $mailing;

    /**
     * The data that should be put in the mailing
     * @var array|null
     */
    protected ?array $mailData;

    /**
     * Returns a new instance of a Mail Notification
     * @return static
     */
    public static function init(): self
    {
        return new self();
    }

    /**
     * Triggers the notification service in order to send a given mailing
     *
     * @return void
     * @throws NotificationServiceException
     */
    public function send()
    {
        if(is_null($this->getMailing()) || is_null($this->getMailData())) {
            throw new NotificationServiceException("Either the mailing or the maildata is not specified!");
        }

        $endpoint = Str::replace(
            "//",
            "/",
            $this->getEndpoint() . "/api/send/mail/" . $this->getMailing()
        );

        $response = Http::withToken($this->getToken())
            ->post($endpoint, $this->getMailData());

        if($response->failed()) {
            $reason = $response->object()->message ?? "No error message available";
            $status = $response->status();
            throw new NotificationServiceException("Status {$status}: Notification could not be sent because: {$reason}");
        }

        ray("MailNotification@send", $endpoint);
    }

    /**
     * @return string|null
     */
    public function getMailing(): ?string
    {
        return $this->mailing;
    }

    /**
     * @param string $mailing
     * @return $this
     */
    public function setMailing(string $mailing): self
    {
        $this->mailing = $mailing;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMailData(): ?array
    {
        return $this->mailData;
    }

    /**
     * @param array $mailData
     * @return $this
     */
    public function setMailData(array $mailData): self
    {
        $this->mailData = $mailData;
        return $this;
    }

    /**
     * Returns the relevant endpoint for the current environment
     *
     * @return string
     */
    protected function getEndpoint(): string
    {
        return match (config('app.env')) {
            "production", "prod" => self::PRODUCTION_URL,
            "staging", "stage" => self::STAGING_URL,
            default => self::DEV_URL,
        };
    }

    /**
     * Returns the relevant token for the current environment
     *
     * @return string
     */
    protected function getToken(): string
    {
        return match (config('app.env')) {
            "production", "prod" => self::PRODUCTION_TOKEN,
            "staging", "stage" => self::STAGING_TOKEN,
            default => self::LOCAL_TOKEN,
        };
    }
}
