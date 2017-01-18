<?php

namespace AmazonAlexaSkill\Response;

/**
 * Class CardImage
 */
class CardImage
{
    /**
     * @var string
     */
    public $smallUrl;

    /**
     * @var string
     */
    public $largeUrl;

    /**
     * Constructor.
     */
    public function __construct($smallUrl, $largeUrl)
    {
        $this->smallUrl = $smallUrl;
        $this->largeUrl = $largeUrl;
    }
}
