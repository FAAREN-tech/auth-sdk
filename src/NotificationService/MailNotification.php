<?php

namespace FaarenTech\FaarenSDK\NotificationService;

use FaarenTech\FaarenSDK\Exceptions\NotificationServiceException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class MailNotification
{
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
     * The data that should be put in the mailing
     * @var NotificationService|null
     */
    protected ?NotificationService $notificationService;

    /**
     * @param  NotificationService|null  $notificationService
     */
    public function __construct(?NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Returns a new instance of a Mail Notification
     *
     * @param NotificationService $notificationService
     * @return static
     */
    public static function init(NotificationService $notificationService): self
    {
        return new self($notificationService);
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
            "production", "prod" => config('faaren-sdk.notification_service.endpoints.production'),
            "staging", "stage" => config('faaren-sdk.notification_service.endpoints.staging'),
            default => config('faaren-sdk.notification_service.endpoints.dev'),
        };
    }

    /**
     * Returns the relevant token for the current environment
     *
     * @return string
     */
    #[Pure] protected function getToken(): string
    {
        return $this->getNotificationService()->getPlainTextToken();
    }

    /**
     * @return NotificationService|null
     */
    public function getNotificationService(): ?NotificationService
    {
        return $this->notificationService;
    }
}
