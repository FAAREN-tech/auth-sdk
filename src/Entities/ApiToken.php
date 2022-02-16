<?php

namespace FaarenTech\FaarenSDK\Entities;

use FaarenTech\FaarenSDK\Exceptions\ApiTokenException;

class ApiToken
{
    protected string $plainTextToken;
    protected \stdClass $details;

    /**
     * @param \stdClass $apiTokenObject
     * @param string $plainTextToken
     */
    public function __construct(\stdClass $apiTokenObject, string $plainTextToken)
    {
        $this->details = $apiTokenObject;
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
     * @throws ApiTokenException
     */
    public function permissions(): array
    {
        if(!$this->details->permissions || !is_array($this->details->permissions)) {
            throw new ApiTokenException();
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
