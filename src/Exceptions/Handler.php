<?php

namespace FaarenTech\FaarenSDK\Exceptions;

use FaarenTech\FaarenSDK\Resources\ResponseCollection;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

/**
 * @deprecated
 */
abstract class Handler extends ExceptionHandler
{
    /**
     * @param $request
     * @param Throwable $e
     * @return ResponseCollection
     */
    public function render($request, Throwable $e)
    {
        return new ResponseCollection($e, false, 500);
    }
}
