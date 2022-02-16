<?php

namespace FaarenTech\AuthSdk;

use FaarenTech\AuthSdk\Interfaces\AuthBagInterface;
use FaarenTech\AuthSdk\Interfaces\AuthSdkInterface;

class FaarenAuth implements AuthSdkInterface
{
    public static function init(string $apiToken): AuthSdkInterface
    {
        // TODO: Implement init() method.
    }

    public function getAuthBag(): AuthBagInterface
    {
        // TODO: Implement getAuthBag() method.
    }

}
