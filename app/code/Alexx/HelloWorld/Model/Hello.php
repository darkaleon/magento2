<?php
declare(strict_types=1);

namespace Alexx\HelloWorld\Model;

use Alexx\HelloWorldApi\Api\Data\HelloApiInterface;

/**
 *
 */
class Hello implements HelloApiInterface
{

    /**@inheritDoc */
    public function getHello(): string
    {
        return  __('Hello world')->__toString();
    }


}
