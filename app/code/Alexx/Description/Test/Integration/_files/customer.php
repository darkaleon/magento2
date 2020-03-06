<?php

$integrationTestSuitePath = __DIR__ . '/../../../../../../../dev/tests/integration/testsuite';

require $integrationTestSuitePath . '/Magento/Sales/_files/default_rollback.php';
require $integrationTestSuitePath . '/Magento/Customer/_files/customer_rollback.php';
require $integrationTestSuitePath . '/Magento/Customer/_files/customer_sample.php';
/** @var \Magento\Customer\Model\Customer $customer */
$customerData = $customer;
$customerRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->get(\Magento\Customer\Api\CustomerRepositoryInterface::class);
$customer = $customerRepository->getById($customerData->getId());
$customer->getExtensionAttributes()->getAllowAddDescription()->setCustomerAllowAddDescription(true);
$customerRepository->save($customer);
