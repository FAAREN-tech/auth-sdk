<?php

namespace FaarenTech\FaarenSDK\Entities;

use FaarenTech\FaarenSDK\Exceptions\AppTokenException;

class AppToken
{
    protected string $plainTextToken;
    protected \stdClass $details;

    /**
     * @param \stdClass $appTokenObject
     * @param string $plainTextToken
     */
    public function __construct(\stdClass $appTokenObject, string $plainTextToken)
    {
        $this->details = $appTokenObject;
        $this->details->plainTextToken = $plainTextToken;
    }

    /**
     * Returns all available details
     *
     * @return \stdClass
     */
    public function details(): \stdClass
    {
        return $this->details;
    }

    /**
     * Returns the token's permissions
     *
     * @return array
     * @throws AppTokenException
     */
    public function permissions(): array
    {
        if(!$this->details->permissions || !is_array($this->details->permissions)) {
            throw new AppTokenException();
        }

        return (array) $this->details->permissions;
    }

    /**
     * Returns the plain text token
     *
     * @return string
     */
    public function getPlainTextToken(): string
    {
        return $this->details->plainTextToken;
    }
}
