<?php

namespace AmazonAlexaSkill\Request;

/**
 * Class Request
 */
class Request
{
    /**
     * @var string
     */
    private $rawRequest;

    /**
     * @var stdClass
     */
    private $parsedRequest;

    /**
     * @var
     */
    private $id;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var stdClass
     */
    private $intent;

    /**
     * @param $json
     *
     * @throws \Exception
     */
    public function __construct($rawRequest)
    {
        $this->validateRequest($rawRequest);
        $this->rawRequest = $rawRequest;
        $this->parsedRequest = $this->parseRawRequest();
        $this->id = $this->parsedRequest->request->requestId;
        $this->locale = $this->parsedRequest->request->locale;
        $this->timestamp = $this->parsedRequest->request->timestamp;
        $this->intent = $this->parsedRequest->request->intent;
    }

    /**
     * Validate the request.
     *
     * @return \InvalidRequestException|null
     */
    private function validateRequest($rawRequest)
    {
        $validator = new RequestValidator($rawRequest);
        if (!$validator->validate(getenv("ALEXA_APPLICATION_ID"))) {
            throw new InvalidRequestException($validator->getErrors());
        }
    }

    /**
     * Parse the raw JSON request.
     *
     * @return stdClass
     */
    private function parseRawRequest()
    {
        $parsedRequest = json_decode($this->rawRequest);
        if (is_null($parsedRequest)) {
            throw new \Exception('Invalid JSON request');
        }
        return $parsedRequest;
    }

    /**
     * Get the intent of the request.
     *
     * @return stdClass
     */
    public function getIntent()
    {
        return $this->intent;
    }
}
