<?php
declare(strict_types=1);

namespace Alexx\Description\Model;

use Alexx\Description\Api\AllowAddDescripitonRepositoryInterface;
use Alexx\Description\Api\Data\AllowAddDescripitonInterface;
use Alexx\Description\Api\Data\AllowAddDescripitonInterfaceFactory;
use Alexx\Description\Model\ResourceModel\AllowAddDescription as ResourceCustomerAllowAddDescription;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Customer allow add product description repository
 */
class AllowAddDescripitonRepository implements AllowAddDescripitonRepositoryInterface
{
    /**@var ResourceCustomerAllowAddDescription */
    private $resourceModel;

    /**@var AllowAddDescripitonInterfaceFactory */
    private $allowAddDescriptionFactory;

    /**
     * @param ResourceCustomerAllowAddDescription $resource
     * @param AllowAddDescripitonInterfaceFactory $allowAddDescriptionFactory
     */
    public function __construct(
        ResourceCustomerAllowAddDescription $resource,
        AllowAddDescripitonInterfaceFactory $allowAddDescriptionFactory
    ) {
        $this->resourceModel = $resource;
        $this->allowAddDescriptionFactory = $allowAddDescriptionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getByCustomer(CustomerInterface $customer): AllowAddDescripitonInterface
    {
        $customerAllowAddDescription = $this->allowAddDescriptionFactory->create();
        $this->resourceModel->load($customerAllowAddDescription, (int)$customer->getId(), 'customer_entity_id');
        if (!$customerAllowAddDescription->getId()) {
            $customerAllowAddDescription->setCustomerAllowAddDescription('0');
            $customerAllowAddDescription->setCustomerEmail($customer->getEmail());
            if ($customer->getId()) {
                $customerAllowAddDescription->setCustomerEntityId($customer->getId());
            }
        }
        return $customerAllowAddDescription;
    }

    /**
     * @inheritDoc
     */
    public function deleteByCustomer(CustomerInterface $customer): void
    {
        $customerAllowAddDescription = $this->getByCustomer($customer);
        if ($customerAllowAddDescription->getId()) {
            $this->delete($customerAllowAddDescription);
        }
    }

    /**
     * @inheritDoc
     */
    public function save(AllowAddDescripitonInterface $customerAllowAddDescription): AllowAddDescripitonInterface
    {
        try {
            $this->resourceModel->save($customerAllowAddDescription);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $customerAllowAddDescription;
    }

    /**
     * @inheritDoc
     */
    public function delete(AllowAddDescripitonInterface $customerAllowAddDescription): void
    {
        try {
            $this->resourceModel->delete($customerAllowAddDescription);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
    }
}
