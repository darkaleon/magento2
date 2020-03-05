<?php

use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

$customerDescriptionRepository = $objectManager->get(DescriptionRepositoryInterface::class);
$customerDescriptionFactory = $objectManager->get(DescriptionInterfaceFactory::class);

try {
    $newCustomerProductDescription =
        $customerDescriptionRepository->getByProductAndCustomer($product->getId(), $customer->getId());
} catch (NoSuchEntityException  $exception) {
    $newCustomerProductDescription = $customerDescriptionFactory->create();
}

$newCustomerProductDescription->setProductEntityId($product->getId());
$newCustomerProductDescription->setCustomerEntityId($customer->getId());
$newCustomerProductDescription->setDescription('Fake description');
$customerDescriptionRepository->save($newCustomerProductDescription);
