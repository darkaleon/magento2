<?php
declare(strict_types=1);

namespace Alexx\Description\Plugin;

use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Model\Config\CustomerAccessManagerToDescription;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Plugin for Magento\Catalog/Model/Product.php
 *
 * Injects extension attribute data for additional_description field
 */
class LoadAdditionalDescriptionExtensionAttributeProductPlugin
{
    /**@var ProductExtensionFactory */
    private $extensionFactory;

    /**@var DescriptionRepositoryInterface */
    private $descriptionRepository;

    /**@var CustomerAccessManagerToDescription */
    private $customerAccessManagerToDescription;

    /**@var DescriptionInterfaceFactory */
    private $descriptionFactory;

    /**
     * @param DescriptionRepositoryInterface $descriptionRepository
     * @param ProductExtensionFactory $extensionFactory
     * @param CustomerAccessManagerToDescription $customerAccessManagerToDescription
     * @param DescriptionInterfaceFactory $descriptionFactory
     */
    public function __construct(
        DescriptionRepositoryInterface $descriptionRepository,
        ProductExtensionFactory $extensionFactory,
        CustomerAccessManagerToDescription $customerAccessManagerToDescription,
        DescriptionInterfaceFactory $descriptionFactory
    ) {
        $this->descriptionRepository = $descriptionRepository;
        $this->extensionFactory = $extensionFactory;
        $this->customerAccessManagerToDescription = $customerAccessManagerToDescription;
        $this->descriptionFactory = $descriptionFactory;
    }

    /**
     * Loads product entity extension attributes
     *
     * @param ProductInterface $entity
     * @param ProductExtensionInterface|null $extension
     *
     * @return ProductExtensionInterface
     */
    public function afterGetExtensionAttributes(
        ProductInterface $entity,
        ProductExtensionInterface $extension = null
    ): ProductExtensionInterface {
        if ($extension === null) {
            $extension = $this->extensionFactory->create();
        }
        if ($extension->getAdditionalDescription() === null) {
            if ($this->customerAccessManagerToDescription->isStorefront()) {
                $customerId = $this->customerAccessManagerToDescription->getCustomerId();
                if ($customerId != null) {
                    $productId = (string)$entity->getId();
                    try {
                        $descriptionEntity = $this->descriptionRepository
                            ->getByProductAndCustomer($productId, $customerId);
                    } catch (NoSuchEntityException $exception) {
                        $descriptionEntity = $this->descriptionFactory->create();
                        $descriptionEntity->setProductEntityId($productId);
                        $descriptionEntity->setCustomerEntityId($customerId);
                    }
                    $extension->setAdditionalDescription($descriptionEntity);
                }
            }
        }
        return $extension;
    }
}
