<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Unit\Plugin;

use Alexx\HelloWorld\Model\Hello;
use Alexx\HelloWorldPlugins\Plugin\AddHelloMessageSuffix;
use PHPUnit\Framework\TestCase;

/**
 * Test after plugin
 */
class AddHelloMessageSuffixTest extends TestCase
{
    /**@var AddHelloMessageSuffix */
    private $addHelloMessageSuffixPlugin;

    /**@var Hello*/
    private $helloModel;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->addHelloMessageSuffixPlugin = new AddHelloMessageSuffix();
        $this->helloModel = new Hello();
    }

    /**
     * Test plugin result
     */
    public function testAfterGetHelloWithSuffix()
    {
        $methodInputString = __('Hello world')->__toString();
        $expectedResult = $methodInputString . '_suffix';
        $this->assertEquals(
            $expectedResult,
            $this->addHelloMessageSuffixPlugin->afterGetHello($this->helloModel, $methodInputString)
        );
    }
}
