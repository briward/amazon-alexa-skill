<?php

namespace AmazonAlexaSkill\Response;

/**
 * Class OutputSpeech
 */
class OutputSpeech
{
    public $type;
    public $text;
    public $ssml;

    public function __construct($type, $text)
    {
        $this->type = $type;
        $this->text = $text;
        $this->ssml = $text;
    }
}
