<?php

namespace Thestandingsection\Core\Alexa\Response;

/**
 * Class Card
 */
class Card
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $text;

    /**
     * @var CardImage
     */
    public $image;

    /**
     * Constructor.
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function setImage(CardImage $cardImage)
    {
        $this->image = $cardImage;
    }
}
