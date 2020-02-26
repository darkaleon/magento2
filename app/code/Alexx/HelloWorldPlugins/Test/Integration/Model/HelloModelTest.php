<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Integration\Model;

use Alexx\HelloWorld\Model\Hello;
use Alexx\HelloWorldApi\Api\Data\HelloApiInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test Hello model
 */
class HelloModelTest extends TestCase
{
    /**@var Hello */
    private $object;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->object = new Hello();
    }

    /**
     * Testing model instance
     */
    public function testImplementsHelloApiInterface()
    {
        $this->assertInstanceOf(HelloApiInterface::class, $this->object);
    }

    /**
     * Testing model getHello result
     */
    public function testModel()
    {
        $inStr = __('Hello world')->__toString();
        $this->assertEquals($inStr, $this->object->getHello());
    }
}
