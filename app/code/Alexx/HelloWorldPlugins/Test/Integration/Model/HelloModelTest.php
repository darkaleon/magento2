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
    private $helloModel;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->helloModel = new Hello();
    }

    /**
     * Testing model instance
     */
    public function testImplementsHelloApiInterface()
    {
        $this->assertInstanceOf(HelloApiInterface::class, $this->helloModel);
    }

    /**
     * Testing model getHello result
     */
    public function testGetHello()
    {
        $expectedResult = __('Hello world')->__toString();
        $this->assertEquals($expectedResult, $this->helloModel->getHello());
    }
}
