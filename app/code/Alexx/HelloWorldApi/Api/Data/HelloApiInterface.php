<?php

namespace Alexx\HelloWorldApi\Api\Data;

/**
 *
 */
interface HelloApiInterface
{
    /**
     * Returns greeting message
     *
     * @return string
     */
    public function getHello(): string ;

}
