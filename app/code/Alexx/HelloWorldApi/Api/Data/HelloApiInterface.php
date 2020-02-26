<?php
declare(strict_types=1);

namespace Alexx\HelloWorldApi\Api\Data;

/**
 * Interface to create greetings
 */
interface HelloApiInterface
{
    /**
     * Returns greeting message
     *
     * @return string
     */
    public function getHello(): string;
}
