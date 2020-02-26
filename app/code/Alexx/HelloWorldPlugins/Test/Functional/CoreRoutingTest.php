<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Functional;

use Magento\Framework\Webapi\Rest\Request;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Api-functional test class
 */
class CoreRoutingTest extends WebapiAbstract
{
    /**
     * Test api response from /rest/V1/hello/
     */
    public function testBasicRoutingExplicitPath()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/hello/',
                'httpMethod' => Request::HTTP_METHOD_GET,
            ],
        ];
        $item = $this->_webApiCall($serviceInfo, []);
        $expected = '<h1>prefix_Hello world_suffix</h1>';
        $this->assertEquals($expected, $item, "Item was retrieved unsuccessfully");
    }
}
