<?php

namespace FaarenTech\FaarenSDK\Request;
use FaarenTech\FaarenSDK\Entities\AppToken;
use FaarenTech\FaarenSDK\Resources\ResponseCollection;
use Illuminate\Foundation\Http\FormRequest;

abstract class FaarenRequest extends FormRequest
{
    /**
     * @param array $errors
     * @return ResponseCollection
     */
    protected function response(array $errors)
    {
        return new ResponseCollection($errors, false, 500);
    }

    /**
     * @return bool
     */
    public function wantsJson(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function expectsJson(): bool
    {
        return true;
    }

    public function validateResolved()
    {
        $validator = $this->getValidatorInstance();

        if ($validator->fails()) {
            $this->failedValidation($validator);
        }

        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }
    }
}
