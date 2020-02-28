<?php
$integrationTestSuitePath = __DIR__ . '/../../../../../../../../dev/tests/integration/testsuite';

require $integrationTestSuitePath . '/Magento/Sales/_files/default_rollback.php';
require $integrationTestSuitePath . '/Magento/Customer/_files/customer_rollback.php';
require $integrationTestSuitePath . '/Magento/Customer/_files/customer_sample.php';
/** @var \Magento\Customer\Model\Customer $customer */

//var_dump($customer);exit();

$customer->setAllowAddDescription(true);
$customer->save();



require $integrationTestSuitePath . '/Magento/Catalog/_files/product_simple.php';
/** @var $product \Magento\Catalog\Model\Product */


$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

//$test=$objectManager->get(\Alexx\Description\Api\Data\DescriptionInterfaceFactory::class);

/*$objectManager->get(\Magento\Framework\Registry::class)->unregister('current_product');
$objectManager->get(\Magento\Framework\Registry::class)->register('current_product', $product);*/



