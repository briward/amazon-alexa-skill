<?php

namespace AmazonAlexaSkill;

use AmazonAlexaSkill\Request\Request;
use AmazonAlexaSkill\Response\Response;

/**
 * Class AmazonAlexaSkill
 */
class AmazonAlexaSkill
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * Constructor
     */
    public function __construct()
    {
        $request = file_get_contents('php://input');
        $this->request = new Request($request);
    }

    /**
     * Handle the request
     *
     * @return Closure
     */
    public function handleRequest(\Closure $closure)
    {
        return $closure($this, $this->request);
    }

    /**
     * Response to Alexa.
     *
     * @return Response
     */
    public function respond()
    {
        $this->response = new Response();
        return $this->response;
    }

    /**
     * Get the response.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response->build();
    }
}
