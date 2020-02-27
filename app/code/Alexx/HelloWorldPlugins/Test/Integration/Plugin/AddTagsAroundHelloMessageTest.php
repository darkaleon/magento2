<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Unit\Plugin;

use Alexx\HelloWorld\Model\Hello;
use Alexx\HelloWorldApi\Api\Data\HelloApiInterface;
use Alexx\HelloWorldPlugins\Plugin\AddTagsAroundHelloMessage;
use PHPUnit\Framework\TestCase;

/**
 * Test around plugin
 */
class AddTagsAroundHelloMessageTest extends TestCase
{
    /**@var AddTagsAroundHelloMessage */
    private $addTagsAroundHelloMessageTestPlugin;

    /**@var Hello */
    private $helloModel;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->addTagsAroundHelloMessageTestPlugin = new AddTagsAroundHelloMessage();
        $this->helloModel = new Hello();
    }

    /**
     * Test plugin result
     */
    public function testAroundGetHello()
    {
        $methodInputString = __('Hello world')->__toString();
        $expectedResult = '<h1>' . $methodInputString . '</h1>';
        $pluginAroundCallback = function () {
            return __('Hello world')->__toString();
        };
        $this->assertEquals(
            $expectedResult,
            $this->addTagsAroundHelloMessageTestPlugin->aroundGetHello(
                $this->helloModel,
                $pluginAroundCallback,
                $methodInputString
            )
        );
    }
}
