<?php

namespace FaarenTech\FaarenSDK\NotificationService;

class NotificationService
{
    protected ?string $plainTextToken;

    public function __construct(string $plainTextToken)
    {
        $this->plainTextToken = $plainTextToken;
    }

    /**
     * Returns a new instance of the Notification Service
     *
     * @param string $plainTextToken
     * @return static
     */
    public static function init(string $plainTextToken): self
    {
        return new self($plainTextToken);
    }

    /**
     * @param string $plainTextToken
     * @return MailNotification
     */
    public function mail(): MailNotification
    {
        return new MailNotification($this->getPlainTextToken());
    }

    /**
     * @return string|null
     */
    public function getPlainTextToken(): ?string
    {
        return $this->plainTextToken;
    }


}
