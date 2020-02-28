<?php
declare(strict_types=1);

namespace Alexx\Description\Plugin;

use Alexx\Description\Api\DescriptionRepositoryInterface;
use Alexx\Description\Api\Data\DescriptionInterfaceFactory;
use Alexx\Description\Model\Config\ConfigForCustomer;
use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Plugin for Catalog/Model/Product.php
 *
 * Injects extension attribute data for additional_description field
 */
class ProductPlugin
{
    /**@var ProductExtensionFactory */
    private $extensionFactory;

    /**@var DescriptionRepositoryInterface */
    private $descriptionRepository;

    /**@var ConfigForCustomer*/
    private $configForCustomer;

    /**@var DescriptionInterfaceFactory */
    private $descriptionFactory;

    /**
     * @param DescriptionRepositoryInterface $descriptionRepository
     * @param ProductExtensionFactory $extensionFactory
     * @param ConfigForCustomer $configForCustomer
     * @param DescriptionInterfaceFactory $descriptionFactory
     */
    public function __construct(
        DescriptionRepositoryInterface $descriptionRepository,
        ProductExtensionFactory $extensionFactory,
        ConfigForCustomer $configForCustomer,
        DescriptionInterfaceFactory $descriptionFactory
    ) {
        $this->descriptionRepository = $descriptionRepository;
        $this->extensionFactory = $extensionFactory;
        $this->configForCustomer = $configForCustomer;
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
            if ($this->configForCustomer->isStorefront()) {
                $productId = (string)$entity->getId();
                $customerId = $this->configForCustomer->getCustomerId();
                if ($customerId != null) {
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
