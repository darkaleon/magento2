<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Plugin;

use Alexx\HelloWorldApi\Api\Data\HelloApiInterface;

/**
 * Plugin that closes in tags
 */
class AddTagsAroundHelloMessage
{
    /**
     * Get hello method interception
     *
     * @param HelloApiInterface $subject
     * @param callable $proceed
     *
     * @return string
     */
    public function aroundGetHello(HelloApiInterface $subject, callable $proceed): string
    {
        return "<h1>" . $proceed() . "</h1>";
    }
}
