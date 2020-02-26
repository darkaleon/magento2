<?php
declare(strict_types=1);

namespace Alexx\Description\Block;

use Alexx\Description\Model\Config\ConfigForCustomer;
use Magento\Catalog\Model\Locator\RegistryLocator;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Alexx\Description\Model\Description;

/**
 * Block which is injected to catalog_product_view layout
 */
class ProductBlock extends Template
{
    /** @var RegistryLocator */
    private $productRegistryLocator;

    /** @var ConfigForCustomer */
    private $configForCustomer;

    /**
     * @param Context $context
     * @param RegistryLocator $productRegistryLocator
     * @param ConfigForCustomer $configForCustomer
     * @param array $data
     */
    public function __construct(
        Context $context,
        RegistryLocator $productRegistryLocator,
        ConfigForCustomer $configForCustomer,
        array $data = []
    ) {
        $this->configForCustomer = $configForCustomer;
        $this->productRegistryLocator = $productRegistryLocator;
        parent::__construct($context, $data);
    }

    /**
     * Retreive extension attribute AdditionalDescription
     *
     * @return Description|null
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function getClientDescription(): Description
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
        return $this->configForCustomer->isDescriptionAddAllowed();
    }
}
