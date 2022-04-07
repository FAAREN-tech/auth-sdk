<?php

namespace FaarenTech\FaarenSDK\NotificationService;

use JetBrains\PhpStorm\Pure;

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
     * @return MailNotification
     */
    #[Pure] public function mail(): MailNotification
    {
        return new MailNotification($this);
    }

    /**
     * @return string|null
     */
    public function getPlainTextToken(): ?string
    {
        return $this->plainTextToken;
    }


}
