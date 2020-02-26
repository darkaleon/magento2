<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Unit\Plugin;

use Alexx\HelloWorld\Model\Hello;
use Alexx\HelloWorldPlugins\Plugin\PluginAfter;
use PHPUnit\Framework\TestCase;

/**
 * Test after plugin interceptor
 */
class PluginAfterTest extends TestCase
{
    /**@var PluginAfter*/
    private $pluginObject;

    /**@var Hello*/
    private $parentObject;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->pluginObject = new PluginAfter();
        $this->parentObject = new Hello();
    }

    /**
     * Test plugin result
     */
    public function testBefore()
    {
        $inStr = __('Hello world')->__toString();
        $outStr = $inStr . '_suffix';
        $this->assertEquals($outStr, $this->pluginObject->afterGetHello($this->parentObject, $inStr));
    }
}
