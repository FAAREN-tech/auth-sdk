<?php

namespace FaarenTech\FaarenSDK\NotificationService;

class NotificationService
{
    /**
     * Returns a new instance of the Notification Service
     *
     * @return static
     */
    public static function init(): self
    {
        return new self();
    }

    /**
     * @return MailNotification
     */
    public function mail(): MailNotification
    {
        return new MailNotification();
    }
}
