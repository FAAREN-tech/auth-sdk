<?php

namespace FaarenTech\FaarenSDK\Request;
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
}