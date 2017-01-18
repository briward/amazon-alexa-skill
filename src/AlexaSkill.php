<?php

namespace Thestandingsection\Core\Alexa;

use Thestandingsection\Core\Alexa\Request\Request;
use Thestandingsection\Core\Alexa\Response\Response;

/**
 * Class AlexaSkill
 */
class AlexaSkill
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
