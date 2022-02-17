<?php

namespace FaarenTech\FaarenSDK\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\App;
use Throwable;

class ResponseCollection extends ResourceCollection
{
    protected bool $success;
    protected int $httpCode;
    protected ?Throwable $exception = null;

    /**
     * @param mixed $resource
     * @param bool $success
     * @param int $httpCode
     */
    public function __construct($resource, bool $success, int $httpCode)
    {
        $this->success = $success;
        $this->httpCode = $httpCode;
        if ($resource instanceof Throwable) {
            $this->exception = $resource;
        } elseif (!is_null($resource)) {
            parent::__construct($resource);
        }
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'success' => $this->success,
            'code' => $this->httpCode,
            'payload' => [
                $this->collection ?? null
            ],
            'error' => $this->errorResponse()
        ];
    }

    /**
     * @return array|null
     */
    private function errorResponse(): ?array
    {
        if (is_null($this->exception)) {
            return null;
        }

        $errorResponse["message"] = $this->exception->getMessage();
        if (App::environment('local')) {
            $errorResponse['stacktrace'] = $this->exception->getTrace();
        }

        return $errorResponse;
    }
}
