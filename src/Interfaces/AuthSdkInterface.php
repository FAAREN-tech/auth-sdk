<?php

namespace FaarenTech\AuthSdk\Interfaces;

interface AuthSdkInterface
{

    public static function init(string $apiToken): AuthSdkInterface;

    /**
     * Returns an implementation of AuthBag
     *
     * @return AuthBagInterface
     */
    public function getAuthBag(): AuthBagInterface;
}
