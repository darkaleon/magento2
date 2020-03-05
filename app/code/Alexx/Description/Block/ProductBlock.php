<?php
declare(strict_types=1);

namespace Alexx\Description\Block;

use Alexx\Description\Api\Data\DescriptionInterface;
use Alexx\Description\Model\Config\CustomerAccessManagerToDescription;
use Magento\Catalog\Model\Locator\RegistryLocator;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Alexx\Description\Model\Description;
use Magento\Framework\Exception\NotFoundException;

/**
 * Block which is injected to catalog_product_view layout
 */
class ProductBlock extends Template
{
    /** @var RegistryLocator */
    private $productRegistryLocator;

    /** @var CustomerAccessManagerToDescription */
    private $customerAccessManagerToDescription;

    /**
     * @param Context $context
     * @param RegistryLocator $productRegistryLocator
     * @param CustomerAccessManagerToDescription $customerAccessManagerToDescription
     * @param array $data
     */
    public function __construct(
        Context $context,
        RegistryLocator $productRegistryLocator,
        CustomerAccessManagerToDescription $customerAccessManagerToDescription,
        array $data = []
    ) {
        $this->customerAccessManagerToDescription = $customerAccessManagerToDescription;
        $this->productRegistryLocator = $productRegistryLocator;
        parent::__construct($context, $data);
    }

    /**
     * Retreive extension attribute AdditionalDescription
     *
     * @return Description|null
     * @throws NotFoundException
     */
    public function getClientDescription(): DescriptionInterface
    {
        return $this->productRegistryLocator->getProduct()->getExtensionAttributes()->getAdditionalDescription();
    }

    /**
     * Check if current costomer allowed to add description to products
     *
     * @return bool
     */
    public function isDescriptionAddAllowed(): bool
    {
        return $this->customerAccessManagerToDescription->isDescriptionAddAllowed();
    }
}
