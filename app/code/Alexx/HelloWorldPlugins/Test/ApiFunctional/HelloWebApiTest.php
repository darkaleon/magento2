<?php
declare(strict_types=1);

namespace Alexx\HelloWorldPlugins\Test\Functional;

use Magento\Framework\Webapi\Rest\Request;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Api-functional test class
 */
class HelloWebApiTest extends WebapiAbstract
{
    /**
     * Test api response from /rest/V1/hello/
     */
    public function testGetHelloWebApi()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => '/V1/hello/',
                'httpMethod' => Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => 'testHelloWebApiSoapV1',
                'operation' => 'testHelloWebApiSoapV1Item',
            ],
        ];
        $webApiCallResult = $this->_webApiCall($serviceInfo, []);
        $expectedResult = '<h1>prefix_Hello world_suffix</h1>';
        $this->assertEquals($expectedResult, $webApiCallResult, "Item was retrieved unsuccessfully");
    }
}
