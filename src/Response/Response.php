<?php

namespace AmazonAlexaSkill\Response;

/**
 * Class Response
 */
class Response
{
    /**
     * @var string
     */
    private $version = "1.0";

    /**
     * @var array
     */
    private $sessionAttributes = [];

    /**
     * @var
     */
    private $outputSpeech;

    /**
     * @var
     */
    private $card;

    /**
     * @var
     */
    private $reprompt;

    /**
     * @var
     */
    private $directives = [];

    /**
     * @var bool
     */
    private $shouldEndSession = true;

    /**
     * Set the output speech.
     *
     * @return Response
     */
    public function withOutputSpeech($type, $text = "", $ssml = "")
    {
        $this->outputSpeech = new OutputSpeech($type, $text, $ssml);
        return $this;
    }

    /**
     * Set the card.
     *
     * @return Response
     */
    public function withCard($type, $title = "", $content = "", $text = "", $smallImageUrl = "", $largeImageUrl = "")
    {
        $this->card = new Card($type);
        $this->card->setTitle($title);
        $this->card->setContent($content);
        $this->card->setText($text);
        $this->card->setImage(new CardImage($smallImageUrl, $largeImageUrl));
        return $this;
    }

    /**
     * Set reprompt output.
     *
     * @return Response
     */
    public function withReprompt($type, $text = "", $ssml = "")
    {
        $this->reprompt = $this->setOutputSpeech($type, $text, $ssml);
        return $this;
    }

    /**
     * Set directives.
     *
     * @return Response
     */
    public function withDirectives($directives)
    {
        $this->directives = $directives;
        return $this;
    }

    /**
     * End the session.
     *
     * @return Response
     */
    public function endSession($bool)
    {
        $this->shouldEndSession = $bool;
        return $this;
    }

    /**
     * Build the output array.
     *
     * @return string
     */
    public function build()
    {
        return [
            "version" => $this->version,
            "sessionAttributes" => $this->sessionAttributes,
            "response" => [
                "outputSpeech" => $this->outputSpeech,
                "card" => $this->card,
                "reprompt" => $this->reprompt,
                "directives" => $this->directives,
                "shouldEndSession" => $this->shouldEndSession
            ]
        ];
    }

}
