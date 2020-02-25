<?php


namespace Alexx\Description\Block;


use Magento\Catalog\Model\Locator\RegistryLocator;
use Magento\Framework\View\Element\Template;
use Alexx\Description\Model\Config\ConfigForCustomer;

class ProductBlock  extends Template
{
    /**@var RegistryLocator */
    private $productRegistryLocator;
    private $configForCustomer;

    public function __construct(Template\Context $context,  RegistryLocator $productRegistryLocator, ConfigForCustomer $configForCustomer,array $data = [])
    {
        $this->configForCustomer = $configForCustomer;
        $this->productRegistryLocator = $productRegistryLocator;
        parent::__construct($context, $data);
    }

    public function getClientDescription(){
      return  $this->productRegistryLocator->getProduct()->getExtensionAttributes()->getAdditionalDescription();
    }
    public function isDescriptionAddAllowed(){
        return  $this->configForCustomer->isDescriptionAddAllowed();
    }
}
