<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Unit\Plugin;

use Alexx\HelloWorld\Model\Hello;
use Alexx\HelloWorldPlugins\Plugin\PluginBefore;
use PHPUnit\Framework\TestCase;

/**
 * Test before plugin interceptor
 */
class PluginBeforeTest extends TestCase
{
    /**@var PluginBefore*/
    private $pluginObject;

    /**@var Hello*/
    private $parentObject;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->pluginObject = new PluginBefore();
        $this->parentObject = new Hello();
    }

    /**
     * Test plugin result
     */
    public function testBefore()
    {
        $inStr = __('Hello world')->__toString();
        $outStr = 'prefix_' . $inStr;
        $this->assertEquals($outStr, $this->pluginObject->afterGetHello($this->parentObject, $inStr));
    }
}
