<?php

namespace Alexx\HelloWorldPlugins\Test\Functional;


class CoreRoutingTest extends \Magento\TestFramework\TestCase\WebapiAbstract
{
    public function testBasicRoutingExplicitPath()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/hello/',
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
        ];
        $item = $this->_webApiCall($serviceInfo, []);

        $expected = '<h1>prefix_Hello world_suffix</h1>';

        $this->assertEquals($expected, $item, "Item was retrieved unsuccessfully");
    }
}
