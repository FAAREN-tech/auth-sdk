<?php

namespace FaarenTech\AuthSdk\Interfaces;

interface AuthBagInterface
{
    /**
     * Returns a list of available permissions
     *
     * @return array
     */
    public function getPermissions(): array;
}
