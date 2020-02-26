<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Unit\Plugin;

use Alexx\HelloWorld\Model\Hello;
use Alexx\HelloWorldApi\Api\Data\HelloApiInterface;
use Alexx\HelloWorldPlugins\Plugin\PluginAround;
use PHPUnit\Framework\TestCase;

/**
 * Test around plugin interceptor
 */
class PluginAroundTest extends TestCase
{
    /**@var PluginAround */
    private $pluginObject;

    /**@var Hello */
    private $parentObject;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->pluginObject = new PluginAround();
        $this->parentObject = new Hello();
    }

    /**
     * Test plugin result
     */
    public function testAround()
    {
        $inStr = __('Hello world')->__toString();
        $outStr = '<h1>' . $inStr . '</h1>';
        $proceed = function () {
            return __('Hello world')->__toString();
        };
        $this->assertEquals($outStr, $this->pluginObject->aroundGetHello($this->parentObject, $proceed, $inStr));
    }
}
