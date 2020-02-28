<?php


namespace Alexx\Description\Test\Integration\Model;

use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use Alexx\Description\Model\Description;

class DesctiptionModelTest extends TestCase
{
    private $modelDescription;

    public function setUp(){
        $objectMannager = Bootstrap::getObjectManager();
        $this->modelDescription = $objectMannager->get(Description::class);
    }

    public function test(){

    }
}
