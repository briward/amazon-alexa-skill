# Alexa Skill

A framework-agnostic wrapper for the Amazon Alexa Skills API written in PHP.

_Not ready for public use._

## Usage

Install via composer:

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/briward/amazon-alexa-skill"
        }
    ],
    "require": {
        "briward/amazon-alexa-skill": "dev-master"
    }
}
```

```php
use AmazonAlexaSkill\Request\Request;
use AmazonAlexaSkill\AmazonAlexaSkill;

$alexa = new AmazonAlexaSkill();

$alexa->handleRequest(function(AmazonAlexaSkill $alexa, Request $request) {
    return $alexa->respond()->withOutputSpeech("PlainText", "Hey!");
});

return json_encode($alexa->getResponse());
```

_Tests to come!_
