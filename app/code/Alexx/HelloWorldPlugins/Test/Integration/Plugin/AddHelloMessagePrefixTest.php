<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Unit\Plugin;

use Alexx\HelloWorld\Model\Hello;
use Alexx\HelloWorldPlugins\Plugin\AddHelloMessagePrefix;
use PHPUnit\Framework\TestCase;

/**
 * Test before plugin
 */
class AddHelloMessagePrefixTest extends TestCase
{
    /**@var AddHelloMessagePrefix */
    private $addHelloMessagePrefixPlugin;

    /**@var Hello*/
    private $helloModel;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->addHelloMessagePrefixPlugin = new AddHelloMessagePrefix();
        $this->helloModel = new Hello();
    }

    /**
     * Test plugin result
     */
    public function testAfterGetHelloWithPrefix()
    {
        $methodInputString = __('Hello world')->__toString();
        $expectedResult = 'prefix_' . $methodInputString;
        $this->assertEquals(
            $expectedResult,
            $this->addHelloMessagePrefixPlugin->afterGetHello($this->helloModel, $methodInputString)
        );
    }
}
